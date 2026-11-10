const fs = require("fs");
const path = require("path");
const XLSX = require("xlsx");

const ENV_PATH = path.join(__dirname, ".env");

if (fs.existsSync(ENV_PATH)) {
  process.loadEnvFile(ENV_PATH);
}

const WEBHOOK_URL = process.env.BITRIX_WEBHOOK_URL;
const IBLOCK_ID = 12;
const TABLE_PATH = path.join(__dirname, "..", "table.xlsx");

function readTable() {
  if (!fs.existsSync(TABLE_PATH)) {
    throw new Error(`Файл не найден: ${TABLE_PATH}`);
  }

  const wb = XLSX.readFile(TABLE_PATH);
  const sheet = wb.Sheets[wb.SheetNames[0]];
  const rows = XLSX.utils.sheet_to_json(sheet);

  if (rows.length === 0) {
    throw new Error("Таблица пустая");
  }

  console.log(`Строк в таблице: ${rows.length}`);
  console.log(`Колонки: ${Object.keys(rows[0]).join(", ")}`);

  const map = new Map();

  for (const row of rows) {
    const barcodeRaw = row["Штрихкод"];
    const nameRaw = row["Название"];

    if (!barcodeRaw || !nameRaw) continue;

    const barcode = String(barcodeRaw).trim();
    let name = String(nameRaw).trim();

    name = name
      .replace(/[\s\u00A0\u202F]*(?:арт\s*:?\s*)?\d{13}\s*$/i, "")
      .trim();

    if (!name) continue;

    map.set(barcode, name);
  }

  console.log(`Уникальных штрихкодов в таблице: ${map.size}`);

  return map;
}

async function fetchBitrixProducts() {
  let allProducts = [];
  let start = 0;
  let hasMore = true;
  let page = 1;

  while (hasMore) {
    const params = new URLSearchParams();

    params.append("select[0]", "id");
    params.append("select[1]", "iblockId");
    params.append("select[2]", "name");
    params.append("select[3]", "property261");
    params.append("filter[iblockId]", IBLOCK_ID);
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.product.list.json?${params.toString()}`;

    console.log(`Страница ${page}, start=${start}...`);

    const response = await fetch(url);
    const result = await response.json();

    if (result.error) {
      throw new Error(`Битрикс вернул ошибку: ${result.error_description}`);
    }

    const products = result.result?.products ?? [];

    allProducts = allProducts.concat(products);

    console.log(
      `   Получено: ${products.length}, накоплено: ${allProducts.length} из ${result.total ?? "?"}`,
    );

    if (result.next) {
      start = result.next;
      page++;

      await new Promise((r) => setTimeout(r, 300));
    } else {
      hasMore = false;
    }
  }

  return allProducts;
}

async function updateProductName(productId, newName) {
  if (!productId) throw new Error("Не передан productId");
  if (!newName) throw new Error("Не передан newName");

  const body = {
    id: productId,
    fields: {
      name: newName,
    },
  };

  const response = await fetch(`${WEBHOOK_URL}/catalog.product.update.json`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });

  const result = await response.json();

  if (result.error) {
    throw new Error(`Битрикс вернул ошибку: ${result.error_description}`);
  }

  return result;
}

async function fetchProductById(productId) {
  if (!productId) throw new Error("Не передан productId");

  const url = `${WEBHOOK_URL}/catalog.product.get.json?id=${productId}`;
  const response = await fetch(url);
  const result = await response.json();

  if (result.error) {
    throw new Error(`Битрикс вернул ошибку: ${result.error_description}`);
  }

  const product = result.result?.product;

  if (!product) {
    throw new Error(`Товар ${productId} не найден`);
  }

  return {
    id: product.id,
    name: product.name,
    parentId: product.parentId?.value ? String(product.parentId.value) : null,
  };
}

function cleanParentName(rawName) {
  if (!rawName) return "";

  let name = String(rawName).trim();

  const protectedParts = [];

  name = name.replace(/\([^)]*\)/g, (match) => {
    const marker = `__PROTECTED_${protectedParts.length}__`;

    protectedParts.push(match);

    return marker;
  });

  name = name.replace(
    /[\s\u00A0\u202F]*№[\s\u00A0\u202F]*\d+[\s\u00A0\u202F]*/g,
    " ",
  );

  name = name.replace(
    /[\s\u00A0\u202F]*\d+(?:[.,]\d+)?[\s\u00A0\u202F]*(?:[мm][гgкk]?|[гg]р?|[мm][лl])(?=\s|$|[^\wа-яё])/gi,
    " ",
  );

  name = name.replace(/[\s\u00A0\u202F]+/g, " ").trim();

  name = name.replace(/__PROTECTED_(\d+)__/g, (_, index) => {
    return protectedParts[Number(index)];
  });

  return name;
}

async function updateParentNames() {
  console.log("Забираем торговые предложения из Битрикса...");

  const offers = await fetchBitrixProducts();

  console.log(`Всего предложений: ${offers.length}`);

  const updatedParents = new Map();

  let updated = 0;
  let skippedNoParent = 0;
  let skippedAlreadyUpdated = 0;
  let failed = 0;

  const logUpdated = [];
  const logFailed = [];

  for (const offer of offers) {
    try {
      const product = await fetchProductById(offer.id);

      if (!product.parentId) {
        skippedNoParent++;
        continue;
      }

      if (updatedParents.has(product.parentId)) {
        skippedAlreadyUpdated++;
        continue;
      }

      const newParentName = cleanParentName(product.name);

      if (!newParentName) {
        console.warn(`Пустое имя после очистки для предложения ${offer.id}`);
        continue;
      }

      await updateProductName(product.parentId, newParentName);

      updatedParents.set(product.parentId, newParentName);
      updated++;

      logUpdated.push({
        offerId: offer.id,
        parentId: product.parentId,
        offerName: product.name,
        newParentName,
      });

      console.log(
        `Головной ${product.parentId}: "${newParentName}" (от предложения ${offer.id})`,
      );

      await new Promise((r) => setTimeout(r, 200));
    } catch (err) {
      failed++;

      logFailed.push({
        offerId: offer.id,
        error: err.message,
      });

      console.error(`Предложение ${offer.id}: ${err.message}`);
    }
  }

  const updatedPath = path.join(__dirname, "updated-parents.json");

  fs.writeFileSync(updatedPath, JSON.stringify(logUpdated, null, 2), "utf-8");

  const failedPath = path.join(__dirname, "failed-parents.json");

  fs.writeFileSync(failedPath, JSON.stringify(logFailed, null, 2), "utf-8");

  console.log("\nИтог по головным товарам:");
  console.log(`   Обновлено головных:        ${updated}`);
  console.log(`   Пропущено (нет родителя):  ${skippedNoParent}`);
  console.log(`   Пропущено (уже обновлён):  ${skippedAlreadyUpdated}`);
  console.log(`   Ошибок:                    ${failed}`);
  console.log(`\nОбновления: ${updatedPath}`);
  console.log(`Ошибки:      ${failedPath}`);
}

async function main() {
  try {
    if (!WEBHOOK_URL) {
      throw new Error("Не задан BITRIX_WEBHOOK_URL в файле .env");
    }

    console.log("Читаем таблицу...");

    const tableMap = readTable();

    console.log("\nЗабираем товары из Битрикса...");

    const products = await fetchBitrixProducts();

    console.log(`\nВсего товаров в Битриксе: ${products.length}`);

    let updated = 0;
    let skippedNoBarcode = 0;
    let skippedNoMatch = 0;
    let skippedSameName = 0;
    let failed = 0;

    const logUpdated = [];
    const logFailed = [];

    for (const product of products) {
      const barcode = product.property261?.value
        ? String(product.property261.value).trim()
        : null;

      if (!barcode) {
        skippedNoBarcode++;
        continue;
      }

      const newName = tableMap.get(barcode);

      if (!newName) {
        skippedNoMatch++;
        continue;
      }

      if (product.name === newName) {
        skippedSameName++;
        continue;
      }

      try {
        await updateProductName(product.id, newName);

        updated++;

        logUpdated.push({
          id: product.id,
          barcode,
          oldName: product.name,
          newName,
        });

        console.log(
          ` Товар ${product.id} (${barcode}): "${product.name}" → "${newName}"`,
        );
      } catch (err) {
        failed++;

        logFailed.push({
          id: product.id,
          barcode,
          error: err.message,
        });

        console.error(` Товар ${product.id} (${barcode}): ${err.message}`);
      }

      await new Promise((r) => setTimeout(r, 200));
    }

    const updatedPath = path.join(__dirname, "updated-names.json");

    fs.writeFileSync(updatedPath, JSON.stringify(logUpdated, null, 2), "utf-8");

    const failedPath = path.join(__dirname, "failed-names.json");

    fs.writeFileSync(failedPath, JSON.stringify(logFailed, null, 2), "utf-8");

    console.log("\n Итог по предложениям:");
    console.log(`   Обновлено:                  ${updated}`);
    console.log(`   Пропущено (нет штрихкода):  ${skippedNoBarcode}`);
    console.log(`   Пропущено (нет в таблице):  ${skippedNoMatch}`);
    console.log(`   Пропущено (имя совпадает):  ${skippedSameName}`);
    console.log(`   Ошибок:                     ${failed}`);

    await updateParentNames();
  } catch (err) {
    console.error("Ошибка:", err.message);
  }
}

main();
