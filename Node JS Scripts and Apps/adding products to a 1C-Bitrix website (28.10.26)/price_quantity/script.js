const fs = require("fs");
const path = require("path");
const XLSX = require("xlsx");

const ENV_PATH = path.join(__dirname, "..", ".env");

if (fs.existsSync(ENV_PATH)) {
  process.loadEnvFile(ENV_PATH);
}

const WEBHOOK_URL = process.env.BITRIX_WEBHOOK_URL;
const TABLE_PATH = path.join(__dirname, "price_quantity_table.xlsx");
const IBLOCK_OFFERS = 12;
const CURRENCY = "RUB";
const CATALOG_GROUP_ID = 1;

async function safeFetch(url, options = {}, maxRetries = 3) {
  for (let attempt = 1; attempt <= maxRetries; attempt++) {
    try {
      const response = await fetch(url, options);
      const text = await response.text();

      if (!response.ok) {
        if (attempt < maxRetries) {
          await new Promise((r) =>
            setTimeout(r, 1000 * Math.pow(2, attempt - 1)),
          );
          continue;
        }
        throw new Error(`HTTP ${response.status}: ${text.slice(0, 200)}`);
      }

      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        if (attempt < maxRetries) {
          await new Promise((r) =>
            setTimeout(r, 1000 * Math.pow(2, attempt - 1)),
          );
          continue;
        }
        throw new Error(`Ответ не JSON: ${text.slice(0, 200)}`);
      }

      return data;
    } catch (err) {
      if (attempt === maxRetries) throw err;
      await new Promise((r) => setTimeout(r, 1000 * Math.pow(2, attempt - 1)));
    }
  }
}

function readPriceTable() {
  if (!fs.existsSync(TABLE_PATH)) {
    throw new Error(`Файл не найден: ${TABLE_PATH}`);
  }

  const wb = XLSX.readFile(TABLE_PATH);
  const sheet = wb.Sheets[wb.SheetNames[0]];
  const rows = XLSX.utils.sheet_to_json(sheet);

  if (rows.length === 0) throw new Error("Таблица пустая");

  console.log(`Строк в таблице: ${rows.length}`);
  console.log(`Колонки: ${Object.keys(rows[0]).join(", ")}`);

  const products = [];

  for (const row of rows) {
    const barcode = row["Штрихкод"] ? String(row["Штрихкод"]).trim() : null;
    const quantity = row["Остаток"] != null ? Number(row["Остаток"]) : null;
    const price = row["Цена"] != null ? Number(row["Цена"]) : null;

    if (!barcode) {
      console.warn("Пропущена строка: нет штрихкода");
      continue;
    }

    products.push({
      property261: { value: barcode },
      quantity: quantity,
      price: price,
    });
  }

  console.log(`\nСформировано товаров: ${products.length}`);
  return products;
}

async function fetchAllOffers() {
  const map = new Map();
  let start = 0;
  let hasMore = true;

  while (hasMore) {
    const params = new URLSearchParams();
    params.append("select[0]", "id");
    params.append("select[1]", "iblockId");
    params.append("select[2]", "property261");
    params.append("select[3]", "quantity");
    params.append("filter[iblockId]", IBLOCK_OFFERS);
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.product.list.json?${params.toString()}`;
    const result = await safeFetch(url);
    if (result.error) {
      throw new Error(`Предложения: ${result.error_description}`);
    }

    for (const product of result.result?.products ?? []) {
      const barcode = product.property261?.value
        ? String(product.property261.value).trim()
        : null;

      if (!barcode) continue;

      map.set(barcode, {
        id: product.id,
        quantity: product.quantity ?? 0,
      });
    }

    console.log(
      `   Предложения: накоплено ${map.size} из ${result.total ?? "?"}`,
    );

    if (result.next) {
      start = result.next;
      await new Promise((r) => setTimeout(r, 300));
    } else {
      hasMore = false;
    }
  }

  return map;
}

async function updateQuantity(offerId, quantity) {
  const body = {
    id: offerId,
    fields: {
      quantity: quantity,
      active: "Y",
    },
  };

  const result = await safeFetch(`${WEBHOOK_URL}/catalog.product.update.json`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(
      `Обновление quantity ${offerId}: ${result.error_description}`,
    );
  }

  return result;
}

async function fetchAllPrices() {
  const map = new Map();
  let start = 0;
  let hasMore = true;

  while (hasMore) {
    const params = new URLSearchParams();
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.price.list.json?${params.toString()}`;
    const result = await safeFetch(url);
    if (result.error) throw new Error(`Цены: ${result.error_description}`);

    for (const price of result.result?.prices ?? []) {
      const productId = price.productId;

      if (!map.has(productId)) {
        map.set(productId, []);
      }

      map.get(productId).push({
        id: price.id,
        price: price.price,
        currency: price.currency,
        catalogGroupId: price.catalogGroupId,
      });
    }

    console.log(`   Цены: накоплено ${map.size} товаров`);

    if (result.next) {
      start = result.next;
      await new Promise((r) => setTimeout(r, 300));
    } else {
      hasMore = false;
    }
  }

  return map;
}

async function updatePrice(priceId, price) {
  const body = {
    id: priceId,
    fields: {
      price: price,
      currency: CURRENCY,
    },
  };

  const result = await safeFetch(`${WEBHOOK_URL}/catalog.price.update.json`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(`Обновление цены ${priceId}: ${result.error_description}`);
  }

  return result;
}

async function createPrice(productId, price) {
  const body = {
    fields: {
      productId: productId,
      catalogGroupId: CATALOG_GROUP_ID,
      price: price,
      currency: CURRENCY,
    },
  };

  const result = await safeFetch(`${WEBHOOK_URL}/catalog.price.add.json`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(`Создание цены ${productId}: ${result.error_description}`);
  }

  return result;
}

async function main() {
  try {
    if (!WEBHOOK_URL) {
      throw new Error("Не задан BITRIX_WEBHOOK_URL в корневом файле .env");
    }

    const tableData = readPriceTable();

    if (tableData.length === 0) {
      console.log("Нет данных для обновления. Выходим.");
      return;
    }

    console.log("\nЗабираем предложения из Битрикса...");
    const offersMap = await fetchAllOffers();
    console.log(`   Итого предложений: ${offersMap.size}`);

    console.log("\nЗабираем все цены...");
    const pricesMap = await fetchAllPrices();
    console.log(`   Итого товаров с ценами: ${pricesMap.size}`);

    console.log("\nОбновляем остатки и цены...");
    let quantityUpdated = 0;
    let priceUpdated = 0;
    let priceCreated = 0;
    let notFound = 0;
    let failed = 0;

    const tableBarcodes = new Set(tableData.map((t) => t.property261.value));

    for (const item of tableData) {
      const barcode = item.property261.value;
      const existing = offersMap.get(barcode);

      try {
        if (!existing) {
          notFound++;
          console.log(`Не найден: ${barcode}`);
          continue;
        }

        if (item.quantity != null) {
          await updateQuantity(existing.id, item.quantity);
          quantityUpdated++;
          await new Promise((r) => setTimeout(r, 200));
        }

        if (item.price != null) {
          const prices = pricesMap.get(existing.id) ?? [];

          if (prices.length > 0) {
            const priceId = prices[0].id;
            await updatePrice(priceId, item.price);
            priceUpdated++;
            console.log(
              `✓ ${barcode}: quantity=${item.quantity}, price=${item.price} (updated)`,
            );
          } else {
            await createPrice(existing.id, item.price);
            priceCreated++;
            console.log(
              `➕ ${barcode}: quantity=${item.quantity}, price=${item.price} (created)`,
            );
          }

          await new Promise((r) => setTimeout(r, 200));
        } else {
          console.log(
            `✓ ${barcode}: quantity=${item.quantity} (цена не указана)`,
          );
        }
      } catch (err) {
        failed++;
        console.error(`${barcode}: ${err.message}`);
      }
    }

    console.log("\nОбнуляем остатки для несовпавших...");

    for (const [barcode, offer] of offersMap) {
      if (!tableBarcodes.has(barcode)) {
        try {
          await updateQuantity(offer.id, 0);
          console.log(`${barcode}: quantity=0`);
          await new Promise((r) => setTimeout(r, 200));
        } catch (err) {
          console.error(`${barcode}: ${err.message}`);
        }
      }
    }

    console.log("\nИтог:");
    console.log(`   Обновлено остатков:    ${quantityUpdated}`);
    console.log(`   Обновлено цен:         ${priceUpdated}`);
    console.log(`   Создано цен:           ${priceCreated}`);
    console.log(`   Не найдено в Битриксе: ${notFound}`);
    console.log(`   Ошибок:                ${failed}`);
  } catch (err) {
    console.error("Ошибка:", err.message);
  }
}

main();
