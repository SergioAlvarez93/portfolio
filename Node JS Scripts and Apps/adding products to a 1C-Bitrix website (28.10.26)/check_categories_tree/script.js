const fs = require("fs");
const path = require("path");

const ENV_PATH = path.join(__dirname, "..", ".env");

if (fs.existsSync(ENV_PATH)) {
  process.loadEnvFile(ENV_PATH);
}

const WEBHOOK_URL = process.env.BITRIX_WEBHOOK_URL;
const IBLOCK_OFFERS = 12;
const IBLOCK_PRODUCTS = 11;

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

async function fetchAllOffers() {
  const offers = [];
  let start = 0;
  let hasMore = true;

  while (hasMore) {
    const params = new URLSearchParams();
    params.append("select[0]", "id");
    params.append("select[1]", "iblockId");
    params.append("select[2]", "name");
    params.append("select[3]", "property261");
    params.append("filter[iblockId]", IBLOCK_OFFERS);
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.product.list.json?${params.toString()}`;
    const result = await safeFetch(url);
    if (result.error)
      throw new Error(`Предложения: ${result.error_description}`);

    for (const product of result.result?.products ?? []) {
      offers.push({
        id: product.id,
        name: product.name,
        barcode: product.property261?.value
          ? String(product.property261.value).trim()
          : null,
      });
    }

    console.log(
      `   Предложения: накоплено ${offers.length} из ${result.total ?? "?"}`,
    );

    if (result.next) {
      start = result.next;
      await new Promise((r) => setTimeout(r, 300));
    } else {
      hasMore = false;
    }
  }

  return offers;
}

async function fetchParentProduct(parentId) {
  const url = `${WEBHOOK_URL}/catalog.product.get.json?id=${parentId}`;
  const result = await safeFetch(url);
  if (result.error)
    throw new Error(`Головной ${parentId}: ${result.error_description}`);

  const product = result.result?.product;
  return {
    id: product?.id,
    name: product?.name,
    iblockSection: product?.iblockSection ?? [],
  };
}

async function fetchAllSections(iblockId) {
  const map = new Map();
  let start = 0;
  let hasMore = true;

  while (hasMore) {
    const params = new URLSearchParams();
    params.append("filter[iblockId]", iblockId);
    params.append("select[0]", "id");
    params.append("select[1]", "name");
    params.append("select[2]", "iblockSectionId");
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.section.list.json?${params.toString()}`;
    const result = await safeFetch(url);
    if (result.error) throw new Error(`Разделы: ${result.error_description}`);

    for (const section of result.result?.sections ?? []) {
      map.set(section.id, {
        name: section.name,
        parentId: section.iblockSectionId,
      });
    }

    console.log(`   Разделы: накоплено ${map.size} из ${result.total ?? "?"}`);

    if (result.next) {
      start = result.next;
      await new Promise((r) => setTimeout(r, 300));
    } else {
      hasMore = false;
    }
  }

  return map;
}

function buildPath(sectionId, sectionsMap) {
  const pathParts = [];
  let currentId = sectionId;

  while (currentId) {
    const section = sectionsMap.get(currentId);
    if (!section) break;

    pathParts.unshift(section.name);
    currentId = section.parentId;
  }

  return pathParts.join(" → ");
}

async function main() {
  try {
    if (!WEBHOOK_URL) {
      throw new Error("Не задан BITRIX_WEBHOOK_URL в корневом файле .env");
    }

    console.log("Забираем торговые предложения...");
    const offers = await fetchAllOffers();
    console.log(`   Итого предложений: ${offers.length}`);

    console.log("\nЗабираем разделы каталога...");
    const sectionsMap = await fetchAllSections(IBLOCK_PRODUCTS);
    console.log(`   Итого разделов: ${sectionsMap.size}`);

    console.log("\nСтроим отчёт...");
    const report = [];
    let noParent = 0;
    let failed = 0;

    for (const offer of offers) {
      try {
        const offerUrl = `${WEBHOOK_URL}/catalog.product.get.json?id=${offer.id}`;
        const offerResult = await safeFetch(offerUrl);
        if (offerResult.error) throw new Error(offerResult.error_description);

        const parentId = offerResult.result?.product?.parentId?.value;

        if (!parentId) {
          noParent++;
          console.log(` ${offer.barcode}: нет parentId`);
          continue;
        }

        const parent = await fetchParentProduct(parentId);
        const sectionIds = parent.iblockSection ?? [];

        const paths = sectionIds
          .map((sectionId) => buildPath(sectionId, sectionsMap))
          .filter((p) => p);

        report.push({
          barcode: offer.barcode,
          offerName: offer.name,
          paths: paths,
        });

        console.log(`✓ ${offer.barcode}: ${paths.length} путей`);

        await new Promise((r) => setTimeout(r, 150));
      } catch (err) {
        failed++;
        console.error(` ${offer.barcode}: ${err.message}`);
      }
    }

    const outputPath = path.join(__dirname, "categories-report.json");
    fs.writeFileSync(outputPath, JSON.stringify(report, null, 2), "utf-8");

    console.log(`\n Итог:`);
    console.log(`   Товаров в отчёте:  ${report.length}`);
    console.log(`   Без родителя:      ${noParent}`);
    console.log(`   Ошибок:            ${failed}`);
    console.log(` Сохранено: ${outputPath}`);
  } catch (err) {
    console.error("Ошибка:", err.message);
  }
}

main();
