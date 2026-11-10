const fs = require("fs");
const path = require("path");
const XLSX = require("xlsx");
const crypto = require("crypto");

const ENV_PATH = path.join(__dirname, ".env");

if (fs.existsSync(ENV_PATH)) {
  process.loadEnvFile(ENV_PATH);
}

const WEBHOOK_URL = process.env.BITRIX_WEBHOOK_URL;
const TABLE_PATH = path.join(__dirname, "table.xlsx");
const IBLOCK_OFFERS = 12;
const IBLOCK_PRODUCTS = 11;

async function safeFetch(url, options = {}, maxRetries = 3) {
  for (let attempt = 1; attempt <= maxRetries; attempt++) {
    try {
      const response = await fetch(url, options);
      const text = await response.text();

      if (!response.ok) {
        console.warn(
          ` HTTP ${response.status}, попытка ${attempt}/${maxRetries}`,
        );

        if (attempt < maxRetries) {
          await new Promise((r) => setTimeout(r, 2000 * attempt));
          continue;
        }

        throw new Error(`HTTP ${response.status}: ${text.slice(0, 200)}`);
      }

      let data;

      try {
        data = JSON.parse(text);
      } catch (e) {
        console.warn(` Ответ не JSON, попытка ${attempt}/${maxRetries}`);
        console.warn(`   Ответ: ${text.slice(0, 200)}`);

        if (attempt < maxRetries) {
          await new Promise((r) => setTimeout(r, 2000 * attempt));
          continue;
        }

        throw new Error(`Ответ не JSON: ${text.slice(0, 200)}`);
      }

      return data;
    } catch (err) {
      if (attempt === maxRetries) throw err;

      console.warn(` Ошибка запроса: ${err.message}, повтор...`);
      await new Promise((r) => setTimeout(r, 2000 * attempt));
    }
  }
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

function formatWeight(value) {
  if (value == null || value === "") return null;

  const str = String(value).trim();

  if (/г\s*$/i.test(str)) return str;

  return `${str} г`;
}

function detectQuantityParams(rowName) {
  const result = {
    property507: null,
    property554: null,
    property555: null,
    property260: null,
    property556: null,
    property553: null,
  };

  if (!rowName) return result;

  const name = String(rowName).trim();

  const hasNumberSign = /№\s*\d+/i.test(name);

  if (!hasNumberSign && /^\s*НАБОР/i.test(name)) {
    result.property556 = { valueEnum: "Набор" };
    return result;
  }

  if (hasNumberSign) {
    const numberMatch = name.match(/№\s*(\d+)/);
    const quantity = numberMatch ? numberMatch[1] : null;

    if (quantity) {
      const hasCaps = /капс/i.test(name);
      const hasTabs = /табс|таб/i.test(name);

      if (hasCaps) {
        result.property507 = { valueEnum: quantity };
        return result;
      }

      if (hasTabs) {
        result.property554 = { valueEnum: quantity };
        return result;
      }
    }
  }

  const piecesMatch = name.match(/(\d+)\s*шт/i);

  if (piecesMatch) {
    result.property553 = { valueEnum: `${piecesMatch[1]} шт` };
    return result;
  }

  const gramMatch = name.match(/(\d+(?:[.,]\d+)?)\s*г(?![а-яё])/i);

  if (gramMatch) {
    result.property555 = { valueEnum: `${gramMatch[1]} г` };
    return result;
  }

  const mlMatch = name.match(/(\d+(?:[.,]\d+)?)\s*мл/i);

  if (mlMatch) {
    result.property260 = { valueEnum: `${mlMatch[1]} мл` };
    return result;
  }

  return result;
}

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

  const products = [];

  for (const row of rows) {
    const rowName = row["Название"] ? String(row["Название"]).trim() : null;
    const barcode = row["Штрихкод"] ? String(row["Штрихкод"]).trim() : null;
    const brandName = row["Бренд"] ? String(row["Бренд"]).trim() : null;
    const grossWeight = row["Масса брутто"] ?? null;
    const netWeight = row["Масса нетто"] ?? null;

    if (!rowName || !barcode) {
      console.warn("Пропущена строка: нет названия или штрихкода");
      continue;
    }

    const normalizedName = rowName
      .replace(/\s*(?:арт\s*:?\s*)?\d{13}\s*$/i, "")
      .trim();

    if (!normalizedName) {
      console.warn(
        `Пропущена строка: пустое название после нормализации (штрихкод ${barcode})`,
      );
      continue;
    }

    const parentName = cleanParentName(normalizedName);
    const quantityParams = detectQuantityParams(rowName);

    products.push({
      iblockId: IBLOCK_OFFERS,
      rowName: rowName,
      nomralizedName: normalizedName,
      parentName: parentName,
      brandName: brandName,
      property261: { value: barcode },
      property270: { value: formatWeight(grossWeight) },
      property509: { value: formatWeight(netWeight) },
      property507: quantityParams.property507,
      property554: quantityParams.property554,
      property555: quantityParams.property555,
      property260: quantityParams.property260,
      property556: quantityParams.property556,
      property553: quantityParams.property553,
    });
  }

  console.log(`\nСформировано товаров: ${products.length}`);

  return { result: { products } };
}

async function fetchBrands() {
  const url = `${WEBHOOK_URL}/brands.get.json`;
  const result = await safeFetch(url);

  if (result.error) throw new Error(`Бренды: ${result.error_description}`);

  const brands = result.result ?? [];
  const map = new Map();

  for (const brand of brands) {
    map.set(brand.name.trim().toLowerCase(), brand.id);
  }

  console.log(`Брендов получено: ${map.size}`);

  return map;
}

async function fetchPropertyEnums(propertyId) {
  const url = `${WEBHOOK_URL}/catalog.productPropertyEnum.list.json?filter[propertyId]=${propertyId}`;
  const result = await safeFetch(url);

  if (result.error) {
    throw new Error(`Свойство ${propertyId}: ${result.error_description}`);
  }

  const enums = result.result?.productPropertyEnums ?? [];
  const map = new Map();

  for (const item of enums) {
    map.set(String(item.value), item.id);
  }

  console.log(`Свойство ${propertyId}: ${map.size} значений`);

  return map;
}

async function createPropertyEnum(propertyId, value) {
  const xmlId = crypto.randomUUID();

  const body = {
    fields: {
      propertyId: propertyId,
      value: value,
      xmlId: xmlId,
    },
  };

  const result = await safeFetch(
    `${WEBHOOK_URL}/catalog.productPropertyEnum.add.json`,
    {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(body),
    },
  );

  if (result.error) {
    throw new Error(
      `Не удалось создать значение "${value}": ${result.error_description}`,
    );
  }

  const newId = result.result?.productPropertyEnum?.id;

  console.log(
    `➕ Создано значение "${value}" (id: ${newId}) для свойства ${propertyId}`,
  );

  return newId;
}

async function enrichProduct(product, brandMap, enumMaps, propertyIds) {
  const enriched = { ...product };

  if (product.brandName) {
    const brandKey = product.brandName.trim().toLowerCase();
    const brandId = brandMap.get(brandKey);

    if (brandId) {
      enriched.property510 = { value: brandId };
    } else {
      console.warn(`Бренд не найден: "${product.brandName}"`);
      enriched.property510 = null;
    }
  } else {
    enriched.property510 = null;
  }

  const quantityProps = [
    "property507",
    "property554",
    "property555",
    "property260",
    "property556",
    "property553",
  ];

  for (const propKey of quantityProps) {
    const propData = product[propKey];

    if (!propData || !propData.valueEnum) {
      enriched[propKey] = null;
      continue;
    }

    const propertyId = propertyIds[propKey];
    const enumValue = String(propData.valueEnum);
    const enumMap = enumMaps[propertyId];

    let enumId = enumMap.get(enumValue);

    if (!enumId) {
      try {
        enumId = await createPropertyEnum(propertyId, enumValue);
        enumMap.set(enumValue, enumId);
      } catch (err) {
        console.error(`${propKey} для "${product.rowName}": ${err.message}`);
        enriched[propKey] = null;
        continue;
      }
    }

    enriched[propKey] = { value: enumId, valueEnum: enumValue };

    await new Promise((r) => setTimeout(r, 150));
  }

  return enriched;
}

async function enrichTable(tableData) {
  const products = tableData.result?.products ?? [];

  if (products.length === 0) {
    throw new Error("Нет товаров для обогащения");
  }

  const brandMap = await fetchBrands();

  const propertyIds = {
    property507: 507,
    property554: 554,
    property555: 555,
    property260: 260,
    property556: 556,
    property553: 553,
  };

  const enumMaps = {};

  for (const [key, id] of Object.entries(propertyIds)) {
    enumMaps[id] = await fetchPropertyEnums(id);
  }

  console.log("\nОбогащаем товары...");

  const enrichedProducts = [];

  for (const product of products) {
    const enriched = await enrichProduct(
      product,
      brandMap,
      enumMaps,
      propertyIds,
    );

    enrichedProducts.push(enriched);

    console.log(`${product.rowName.slice(0, 60)}...`);
  }

  console.log(`\nОбогащено товаров: ${enrichedProducts.length}`);

  return { result: { products: enrichedProducts } };
}

function normalizeKey(str) {
  if (!str) return "";

  return str
    .replace(/[\u00A0\u202F]/g, " ")
    .replace(/\s+/g, " ")
    .trim()
    .toLowerCase();
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
    params.append("select[3]", "parentId");
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
        parentId: product.parentId?.value
          ? String(product.parentId.value)
          : null,
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

async function fetchAllProducts() {
  const map = new Map();
  let start = 0;
  let hasMore = true;

  while (hasMore) {
    const params = new URLSearchParams();

    params.append("select[0]", "id");
    params.append("select[1]", "iblockId");
    params.append("select[2]", "name");
    params.append("select[3]", "property74");
    params.append("filter[iblockId]", IBLOCK_PRODUCTS);
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.product.list.json?${params.toString()}`;
    const result = await safeFetch(url);

    if (result.error) {
      throw new Error(`Головные: ${result.error_description}`);
    }

    for (const product of result.result?.products ?? []) {
      if (product.name) {
        map.set(normalizeKey(product.name), {
          id: product.id,
          brandId: product.property74?.value ?? null,
        });
      }
    }

    console.log(`   Головные: накоплено ${map.size} из ${result.total ?? "?"}`);

    if (result.next) {
      start = result.next;
      await new Promise((r) => setTimeout(r, 300));
    } else {
      hasMore = false;
    }
  }

  return map;
}

function generateCode(name, uniqueSuffix = "") {
  if (!name) return "";

  const translitMap = {
    а: "a",
    б: "b",
    в: "v",
    г: "g",
    д: "d",
    е: "e",
    ё: "e",
    ж: "zh",
    з: "z",
    и: "i",
    й: "y",
    к: "k",
    л: "l",
    м: "m",
    н: "n",
    о: "o",
    п: "p",
    р: "r",
    с: "s",
    т: "t",
    у: "u",
    ф: "f",
    х: "h",
    ц: "ts",
    ч: "ch",
    ш: "sh",
    щ: "sch",
    ъ: "",
    ы: "y",
    ь: "",
    э: "e",
    ю: "yu",
    я: "ya",
  };

  let code = String(name)
    .toLowerCase()
    .split("")
    .map((char) => translitMap[char] ?? char)
    .join("")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "")
    .replace(/-+/g, "-")
    .slice(0, 100);

  if (uniqueSuffix) {
    code = `${code}-${uniqueSuffix}`;
  }

  return code;
}

async function createParentProduct(parentName, brandId, uniqueSuffix = "") {
  const fields = {
    iblockId: IBLOCK_PRODUCTS,
    name: parentName,
    type: 3,
    code: generateCode(parentName, uniqueSuffix),
  };

  if (brandId) {
    fields.property74 = { value: brandId };
  }

  const body = { fields };

  const result = await safeFetch(`${WEBHOOK_URL}/catalog.product.add.json`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(
      `Создание головного "${parentName}": ${result.error_description}`,
    );
  }

  const id = result.result?.element?.id;
  const actualType = result.result?.element?.type;

  console.log(
    `➕ Создан головной "${parentName}" (id: ${id}, type: ${actualType}, code: ${fields.code}, brand: ${brandId ?? "нет"})`,
  );

  return id;
}

async function updateParentProduct(
  parentId,
  brandId,
  parentName = null,
  uniqueSuffix = "",
) {
  const fields = {};

  if (brandId) {
    fields.property74 = { value: brandId };
  }

  if (parentName) {
    fields.code = generateCode(parentName, uniqueSuffix || String(parentId));
  }

  const body = {
    id: parentId,
    fields: fields,
  };

  const result = await safeFetch(`${WEBHOOK_URL}/catalog.product.update.json`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(
      `Обновление головного ${parentId}: ${result.error_description}`,
    );
  }

  console.log(
    `Обновлён головной ${parentId} (brand: ${brandId ?? "нет"}, code: ${fields.code ?? "не менялся"})`,
  );

  return result;
}

function buildOfferFields(item) {
  const fields = {
    iblockId: item.iblockId,
    name: item.nomralizedName,
  };

  const propKeys = [
    "property261",
    "property270",
    "property509",
    "property507",
    "property554",
    "property555",
    "property260",
    "property556",
    "property553",
    "property510",
  ];

  for (const key of propKeys) {
    if (item[key] !== null && item[key] !== undefined) {
      fields[key] = item[key];
    }
  }

  return fields;
}

async function updateOffer(offerId, fields) {
  const body = { id: offerId, fields };

  const result = await safeFetch(`${WEBHOOK_URL}/catalog.product.update.json`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(`Обновление ${offerId}: ${result.error_description}`);
  }

  return result;
}

async function createOffer(fields) {
  const body = { fields };

  const result = await safeFetch(`${WEBHOOK_URL}/catalog.product.add.json`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(`Создание предложения: ${result.error_description}`);
  }

  return result.result?.element?.id;
}

async function pushToBitrix(finalTableData) {
  const items = finalTableData.result?.products ?? [];

  if (items.length === 0) {
    throw new Error("Нет товаров для отправки");
  }

  console.log("Забираем предложения...");

  const offersMap = await fetchAllOffers();

  console.log(`   Итого предложений в Map: ${offersMap.size}`);

  console.log("\nЗабираем головные...");

  const productsMap = await fetchAllProducts();

  console.log(`   Итого головных в Map: ${productsMap.size}`);

  console.log("\nОбрабатываем товары...");

  const logByParent = new Map();

  let updated = 0;
  let created = 0;
  let parentsCreated = 0;
  let failed = 0;

  for (const item of items) {
    try {
      const barcode = item.property261?.value;
      const existing = offersMap.get(barcode);

      let parentId;
      let parentKey = null;

      if (existing && existing.parentId) {
        parentId = existing.parentId;
      } else {
        parentKey = normalizeKey(item.parentName);

        const parentData = productsMap.get(parentKey);

        if (parentData) {
          parentId = parentData.id;
        } else {
          const brandId = item.property510?.value ?? null;
          const barcodeSuffix = item.property261?.value ?? "";

          parentId = await createParentProduct(
            item.parentName,
            brandId,
            barcodeSuffix,
          );

          productsMap.set(parentKey, { id: parentId, brandId: brandId });

          parentsCreated++;

          await new Promise((r) => setTimeout(r, 300));
        }
      }

      const brandId = item.property510?.value ?? null;

      const parentDataForBrand = productsMap.get(normalizeKey(item.parentName));

      if (
        parentDataForBrand &&
        brandId &&
        parentDataForBrand.brandId !== brandId
      ) {
        try {
          const barcodeSuffix = item.property261?.value ?? "";

          await updateParentProduct(
            parentId,
            brandId,
            item.parentName,
            barcodeSuffix,
          );

          parentDataForBrand.brandId = brandId;

          await new Promise((r) => setTimeout(r, 200));
        } catch (err) {
          console.error(
            `Не удалось обновить головной ${parentId}: ${err.message}`,
          );
        }
      }

      const fields = buildOfferFields(item);

      fields.parentId = { value: String(parentId) };

      let offerId;
      let action;

      if (existing) {
        await updateOffer(existing.id, fields);

        offerId = existing.id;
        action = "updated";

        updated++;
      } else {
        offerId = await createOffer(fields);

        action = "created";
        created++;
      }

      const logKey = String(parentId);

      if (!logByParent.has(logKey)) {
        logByParent.set(logKey, {
          parentId: parentId,
          parentName: item.parentName,
          offers: [],
        });
      }

      logByParent.get(logKey).offers.push({
        ...item,
        id: offerId,
        action: action,
      });

      console.log(
        `${action === "created" ? "➕" : "✓"} ${barcode} → parent ${parentId}`,
      );

      await new Promise((r) => setTimeout(r, 200));
    } catch (err) {
      failed++;
      console.error(`"${item.rowName}": ${err.message}`);
    }
  }

  const log = [...logByParent.values()];

  console.log("\n Итог:");
  console.log(`   Обновлено предложений: ${updated}`);
  console.log(`   Создано предложений:   ${created}`);
  console.log(`   Создано головных:      ${parentsCreated}`);
  console.log(`   Ошибок:                ${failed}`);

  return log;
}

async function main() {
  try {
    if (!WEBHOOK_URL) {
      throw new Error("Не задан BITRIX_WEBHOOK_URL в файле .env");
    }

    const tableData = readTable();

    if (!tableData.result?.products?.length) {
      console.log("Нет товаров после чтения таблицы. Выходим.");
      return;
    }

    const finalTableData = await enrichTable(tableData);

    const outputPath = path.join(__dirname, "final-table-data.json");

    fs.writeFileSync(
      outputPath,
      JSON.stringify(finalTableData, null, 2),
      "utf-8",
    );

    console.log(`\n Сохранено: ${outputPath}`);

    console.log("\n Отправляем в Битрикс...");

    const log = await pushToBitrix(finalTableData);

    const logPath = path.join(__dirname, "push-log.json");

    fs.writeFileSync(logPath, JSON.stringify(log, null, 2), "utf-8");

    console.log(`\n Лог сохранён: ${logPath}`);
  } catch (err) {
    console.error("Ошибка:", err.message);
  }
}

main();
