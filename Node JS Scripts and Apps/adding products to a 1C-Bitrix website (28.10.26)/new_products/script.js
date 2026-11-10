require("dotenv").config();

const fs = require("fs");
const path = require("path");
const XLSX = require("xlsx");

const WEBHOOK_URL = process.env.BITRIX_WEBHOOK_URL;
const TABLE_PATH = path.join(__dirname, "new_products.xlsx");
const IBLOCK_OFFERS = 12;
const IBLOCK_PRODUCTS = 11;
const NEW_PRODUCT_SECTION_ID = 340;
const NEW_PRODUCT_ENUM_VALUE = "17";

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

function normalizeName(rawName) {
  if (!rawName) return "";

  return String(rawName)
    .trim()
    .replace(/[\s\u00A0\u202F]*(?:арт\s*:?\s*)?\d{13}\s*$/i, "")
    .trim();
}

function readNewProductsTable() {
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

  const items = [];

  for (const row of rows) {
    const raw = row["Новинки"] ? String(row["Новинки"]).trim() : null;

    if (!raw) continue;

    const name = normalizeName(raw);

    if (!name) continue;

    items.push({ raw, name });
  }

  console.log(`\nСформировано названий: ${items.length}`);

  return items;
}

async function fetchAllOffers() {
  const map = new Map();

  let start = 0;
  let hasMore = true;

  while (hasMore) {
    const params = new URLSearchParams();

    params.append("select[0]", "id");
    params.append("select[1]", "iblockId");
    params.append("select[2]", "name");
    params.append("select[3]", "parentId");
    params.append("filter[iblockId]", IBLOCK_OFFERS);
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.product.list.json?${params.toString()}`;
    const result = await safeFetch(url);

    if (result.error) {
      throw new Error(`Предложения: ${result.error_description}`);
    }

    for (const product of result.result?.products ?? []) {
      if (product.name) {
        map.set(product.name.trim(), {
          id: product.id,
          parentId: product.parentId?.value
            ? String(product.parentId.value)
            : null,
        });
      }
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

async function fetchAllParents() {
  const map = new Map();

  let start = 0;
  let hasMore = true;

  while (hasMore) {
    const params = new URLSearchParams();

    params.append("select[0]", "id");
    params.append("select[1]", "iblockId");
    params.append("select[2]", "name");
    params.append("select[3]", "property73");
    params.append("select[4]", "iblockSection");
    params.append("filter[iblockId]", IBLOCK_PRODUCTS);
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.product.list.json?${params.toString()}`;
    const result = await safeFetch(url);

    if (result.error) {
      throw new Error(`Головные: ${result.error_description}`);
    }

    for (const product of result.result?.products ?? []) {
      map.set(product.id, {
        name: product.name,
        property73: product.property73?.[0]?.valueEnum ?? null,
        iblockSection: product.iblockSection ?? [],
      });
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

async function updateParentNewFlag(parentId, isNew, currentSections) {
  const fields = {};

  if (isNew) {
    const sections = [...currentSections];

    if (!sections.includes(NEW_PRODUCT_SECTION_ID)) {
      sections.push(NEW_PRODUCT_SECTION_ID);
    }

    fields.iblockSection = sections;
    fields.property73 = [{ value: "17" }];
  } else {
    const sections = currentSections.filter(
      (id) => id !== NEW_PRODUCT_SECTION_ID,
    );

    fields.iblockSection = sections;
    fields.property73 = [];
  }

  const body = {
    id: parentId,
    fields,
  };

  const result = await safeFetch(`${WEBHOOK_URL}/catalog.product.update.json`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(
      `Обновление головного ${parentId}: ${result.error_description}`,
    );
  }

  return result;
}

async function main() {
  try {
    if (!WEBHOOK_URL) {
      throw new Error("Не задан BITRIX_WEBHOOK_URL в файле .env");
    }

    const newItems = readNewProductsTable();

    if (newItems.length === 0) {
      console.log("Нет названий новинок. Выходим.");
      return;
    }

    console.log("\nЗабираем торговые предложения...");

    const offersMap = await fetchAllOffers();

    console.log(`   Итого предложений: ${offersMap.size}`);

    console.log("\nЗабираем головные товары...");

    const parentsMap = await fetchAllParents();

    console.log(`   Итого головных: ${parentsMap.size}`);

    const matched = [];
    const notMatched = [];

    const targetParentIds = new Set();

    console.log("\nСопоставляем названия из таблицы с предложениями...");

    for (const item of newItems) {
      const offer = offersMap.get(item.name);

      if (!offer) {
        notMatched.push({
          raw: item.raw,
          name: item.name,
          reason: "Предложение не найдено в Битриксе",
        });

        console.warn(` Не найдено: "${item.name}"`);

        continue;
      }

      if (!offer.parentId) {
        notMatched.push({
          raw: item.raw,
          name: item.name,
          reason: "У предложения нет parentId",
        });

        console.warn(` Нет parentId: "${item.name}"`);

        continue;
      }

      matched.push({
        raw: item.raw,
        name: item.name,
        offerId: offer.id,
        parentId: offer.parentId,
      });

      targetParentIds.add(String(offer.parentId));
    }

    console.log(`\nСовпало: ${matched.length}`);
    console.log(`Не совпало: ${notMatched.length}`);

    console.log(
      `Головных, которые должны быть новинками: ${targetParentIds.size}`,
    );

    console.log("\nОбновляем флаги новинок...");

    const added = [];
    const removed = [];
    const skipped = [];
    const failed = [];

    for (const [parentId, parent] of parentsMap) {
      const parentIdStr = String(parentId);
      const shouldBeNew = targetParentIds.has(parentIdStr);

      const isCurrentlyNew =
        parent.property73 === "Новинка" ||
        parent.property73 === NEW_PRODUCT_ENUM_VALUE;

      const hasSection = parent.iblockSection.includes(NEW_PRODUCT_SECTION_ID);

      try {
        if (shouldBeNew) {
          if (isCurrentlyNew && hasSection) {
            skipped.push({
              parentId,
              name: parent.name,
            });

            console.log(`✓ ${parentId} — уже новинка`);

            continue;
          }

          await updateParentNewFlag(parentId, true, parent.iblockSection);

          added.push({
            parentId,
            name: parent.name,
          });

          console.log(`➕ ${parentId} — добавлен в новинки`);
        } else {
          if (!isCurrentlyNew && !hasSection) {
            skipped.push({
              parentId,
              name: parent.name,
            });

            continue;
          }

          await updateParentNewFlag(parentId, false, parent.iblockSection);

          removed.push({
            parentId,
            name: parent.name,
          });

          console.log(`➖ ${parentId} — убран из новинок`);
        }

        await new Promise((r) => setTimeout(r, 200));
      } catch (err) {
        failed.push({
          parentId,
          name: parent.name,
          error: err.message,
        });

        console.error(` ${parentId}: ${err.message}`);
      }
    }

    const report = {
      summary: {
        totalInTable: newItems.length,
        matched: matched.length,
        notMatched: notMatched.length,
        parentsAdded: added.length,
        parentsRemoved: removed.length,
        parentsSkipped: skipped.length,
        failed: failed.length,
        timestamp: new Date().toISOString(),
      },
      matched: matched,
      notMatched: notMatched,
      parentsAdded: added,
      parentsRemoved: removed,
      failed: failed,
    };

    const reportPath = path.join(__dirname, "new-products-report.json");

    fs.writeFileSync(reportPath, JSON.stringify(report, null, 2), "utf-8");

    console.log("\n Итог:");
    console.log(`   Всего в таблице:      ${newItems.length}`);
    console.log(`   Совпало с сайтом:     ${matched.length}`);
    console.log(`   Не совпало:           ${notMatched.length}`);
    console.log(`   Добавлено в новинки:  ${added.length}`);
    console.log(`   Убрано из новинок:    ${removed.length}`);
    console.log(`   Пропущено (без изменений): ${skipped.length}`);
    console.log(`   Ошибок:               ${failed.length}`);
    console.log(`\n Отчёт сохранён: ${reportPath}`);
  } catch (err) {
    console.error("Ошибка:", err.message);
  }
}

main();
