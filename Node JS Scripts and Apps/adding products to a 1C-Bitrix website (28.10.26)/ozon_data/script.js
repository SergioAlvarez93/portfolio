const fs = require("fs");
const path = require("path");

require("dotenv").config({
  path: path.join(__dirname, "..", ".env"),
});

const WEBHOOK_URL = process.env.BITRIX_WEBHOOK_URL;
const IBLOCK_OFFERS = 12;
const OZON_URL = "https://api-seller.ozon.ru/v4/product/info/attributes";

const BRANDS = [
  {
    name: "Mishido",
    clientId: process.env.OZON_MISHIDO_CLIENT_ID,
    apiKey: process.env.OZON_MISHIDO_API_KEY,
  },
  {
    name: "Nutrafarm",
    clientId: process.env.OZON_NUTRAFARM_CLIENT_ID,
    apiKey: process.env.OZON_NUTRAFARM_API_KEY,
  },
  {
    name: "Biocord Sport",
    clientId: process.env.OZON_BIOCORD_CLIENT_ID,
    apiKey: process.env.OZON_BIOCORD_API_KEY,
  },
  {
    name: "Hoko",
    clientId: process.env.OZON_HOKO_CLIENT_ID,
    apiKey: process.env.OZON_HOKO_API_KEY,
  },
];

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
        throw new Error(`HTTP ${response.status}: ${text.slice(0, 300)}`);
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
        throw new Error(`Ответ не JSON: ${text.slice(0, 300)}`);
      }

      return data;
    } catch (err) {
      if (attempt === maxRetries) throw err;
      await new Promise((r) => setTimeout(r, 1000 * Math.pow(2, attempt - 1)));
    }
  }
}

function stripHtml(text) {
  if (!text) return "";

  let result = String(text);
  result = result.replace(/<[^>]*>/g, "");
  result = result
    .replace(/&nbsp;/g, " ")
    .replace(/&amp;/g, "&")
    .replace(/&lt;/g, "<")
    .replace(/&gt;/g, ">")
    .replace(/&quot;/g, '"')
    .replace(/&#39;/g, "'")
    .replace(/&mdash;/g, "—")
    .replace(/&ndash;/g, "–")
    .replace(/&laquo;/g, "«")
    .replace(/&raquo;/g, "»")
    .replace(/&hellip;/g, "...")
    .replace(/&copy;/g, "©")
    .replace(/&reg;/g, "®")
    .replace(/&trade;/g, "™")
    .replace(/&#(\d+);/g, (_, code) => String.fromCharCode(Number(code)))
    .replace(/&#x([0-9A-Fa-f]+);/g, (_, code) =>
      String.fromCharCode(parseInt(code, 16)),
    );

  result = result.replace(/\s+/g, " ").trim();
  return result;
}

function truncateText(text, maxLength = 100) {
  if (!text) return "";

  const clean = stripHtml(text);

  if (clean.length <= maxLength) {
    return clean;
  }

  let truncated = clean.slice(0, maxLength);
  const lastSpace = truncated.lastIndexOf(" ");

  if (lastSpace > 0) {
    truncated = truncated.slice(0, lastSpace);
  }

  return truncated.trim() + "...";
}

async function fetchBrandProducts(brand) {
  const body = {
    filter: {
      visibility: "ALL",
    },
    limit: 1000,
    sort_dir: "ASC",
  };

  const result = await safeFetch(OZON_URL, {
    method: "POST",
    headers: {
      "Client-Id": brand.clientId,
      "Api-Key": brand.apiKey,
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body),
  });

  if (result.error) {
    throw new Error(`Ozon [${brand.name}]: ${JSON.stringify(result.error)}`);
  }

  const items = result.result ?? [];
  console.log(`   [${brand.name}] получено: ${items.length}`);

  return items.map((item) => ({ ...item, __brand: brand.name }));
}

function getAttrValue(attributes, attrId) {
  if (!Array.isArray(attributes)) return null;

  const attr = attributes.find((a) => a.id === attrId);
  if (!attr || !Array.isArray(attr.values) || attr.values.length === 0) {
    return null;
  }

  return attr.values[0].value ?? null;
}

function getAttrValues(attributes, attrId) {
  if (!Array.isArray(attributes)) return [];

  const attr = attributes.find((a) => a.id === attrId);
  if (!attr || !Array.isArray(attr.values)) return [];

  return attr.values.map((v) => v.value).filter(Boolean);
}

function buildDetailText(attributes) {
  const parts = [];

  const mainDesc = getAttrValue(attributes, 4191);
  if (mainDesc && mainDesc.trim()) {
    parts.push(`<h2>Подробное описание</h2>\n${mainDesc.trim()}`);
  }

  let composition = getAttrValue(attributes, 8050);
  if (composition && composition.trim()) {
    let text = composition.trim();
    text = text.replace(/^состав\s*:\s*/i, "");

    if (text.length > 0) {
      text = text.charAt(0).toLowerCase() + text.slice(1);
    }

    parts.push(`<strong>Состав</strong>\n${text}`);
  }

  const usage = getAttrValue(attributes, 8048);
  if (usage && usage.trim()) {
    parts.push(`<strong>Способ применения</strong>\n${usage.trim()}`);
  }

  const indications = getAttrValue(attributes, 9388);
  if (indications && indications.trim()) {
    parts.push(`<strong>Показания</strong>\n${indications.trim()}`);
  }

  return parts.join("\n\n");
}

function buildPreviewText(attributes) {
  const mainDesc = getAttrValue(attributes, 4191);
  if (!mainDesc) return "";

  const text = mainDesc.trim();
  return text.length > 150 ? text.slice(0, 150) : text;
}

function buildProperty558(attributes) {
  const values = getAttrValues(attributes, 22445);
  if (values.length === 0) return "";

  return values
    .map((v, i) => {
      const trimmed = String(v).trim();

      if (i === 0) {
        return trimmed.charAt(0).toUpperCase() + trimmed.slice(1);
      }

      return trimmed.charAt(0).toLowerCase() + trimmed.slice(1);
    })
    .join(", ");
}

function normalizeOzonProduct(item) {
  const attributes = item.attributes ?? [];

  return {
    property261: String(item.offer_id ?? "").trim(),
    detailText: buildDetailText(attributes),
    previewText: buildPreviewText(attributes),
    property557: getAttrValue(attributes, 9008) ?? "",
    property558: buildProperty558(attributes),
    property559: "БАД. НЕ ЯВЛЯЕТСЯ ЛЕКАРСТВЕННЫМ СРЕДСТВОМ",
    __brand: item.__brand,
    __ozonId: item.id,
    __name: item.name,
  };
}

async function fetchOzonData() {
  const allProducts = [];

  for (const brand of BRANDS) {
    console.log(`\nЗабираем ${brand.name}...`);
    const items = await fetchBrandProducts(brand);

    for (const item of items) {
      allProducts.push(normalizeOzonProduct(item));
    }

    await new Promise((r) => setTimeout(r, 500));
  }

  console.log(`\nВсего товаров с Ozon: ${allProducts.length}`);

  const output = { result: { products: allProducts } };
  const outputPath = path.join(__dirname, "ozon-normalized.json");

  fs.writeFileSync(outputPath, JSON.stringify(output, null, 2), "utf-8");
  console.log(` Сохранено: ${outputPath}`);

  return allProducts;
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
      map.set(barcode, product.id);
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

function buildUpdateFields(ozonItem) {
  const fields = {};

  if (ozonItem.detailText && ozonItem.detailText.trim()) {
    let detailHtml = ozonItem.detailText;
    detailHtml = detailHtml.replace(/\n\n/g, "<br><br>");
    detailHtml = detailHtml.replace(/\n/g, "<br>");

    fields.detailText = detailHtml;
    fields.detailTextType = "html";
  }

  if (ozonItem.previewText && ozonItem.previewText.trim()) {
    const cleanPreview = truncateText(ozonItem.previewText, 100);

    if (cleanPreview) {
      fields.previewText = cleanPreview;
      fields.previewTextType = "text";
    }
  }

  if (ozonItem.property557 && String(ozonItem.property557).trim()) {
    fields.property557 = { value: String(ozonItem.property557) };
  }

  if (ozonItem.property558 && String(ozonItem.property558).trim()) {
    fields.property558 = { value: String(ozonItem.property558) };
  }

  if (ozonItem.property559 && String(ozonItem.property559).trim()) {
    fields.property559 = { value: String(ozonItem.property559) };
  }

  return fields;
}

async function main() {
  try {
    if (!WEBHOOK_URL) {
      throw new Error("Не задан BITRIX_WEBHOOK_URL в корневом файле .env");
    }

    for (const brand of BRANDS) {
      if (!brand.clientId || !brand.apiKey) {
        throw new Error(
          `Не заданы Ozon Client ID или API Key для ${brand.name}`,
        );
      }
    }

    console.log("=== ЗАБИРАЕМ ДАННЫЕ С OZON ===");
    const ozonProducts = await fetchOzonData();

    if (ozonProducts.length === 0) {
      console.log("Нет данных с Ozon. Выходим.");
      return;
    }

    console.log("\n=== ЗАБИРАЕМ ПРЕДЛОЖЕНИЯ ИЗ БИТРИКСА ===");
    const offersMap = await fetchAllOffers();
    console.log(`   Итого предложений: ${offersMap.size}`);

    console.log("\n=== ОБНОВЛЯЕМ ТОВАРЫ ===");
    let updated = 0;
    let notFound = 0;
    let skipped = 0;
    let failed = 0;

    const logUpdated = [];
    const logNotFound = [];
    const logFailed = [];

    for (const ozonItem of ozonProducts) {
      const barcode = ozonItem.property261;

      if (!barcode) {
        skipped++;
        continue;
      }

      const offerId = offersMap.get(barcode);

      if (!offerId) {
        notFound++;

        logNotFound.push({
          barcode,
          name: ozonItem.__name,
          brand: ozonItem.__brand,
        });

        console.log(` Не найден в Битриксе: ${barcode} (${ozonItem.__brand})`);
        continue;
      }

      const fields = buildUpdateFields(ozonItem);

      if (Object.keys(fields).length === 0) {
        skipped++;
        console.log(`⏭ Пропущен (нечего обновлять): ${barcode}`);
        continue;
      }

      try {
        await updateOffer(offerId, fields);
        updated++;

        logUpdated.push({
          offerId,
          barcode,
          brand: ozonItem.__brand,
          fields: Object.keys(fields),
        });

        console.log(
          `✓ ${barcode} → id ${offerId} (${Object.keys(fields).join(", ")})`,
        );
      } catch (err) {
        failed++;

        logFailed.push({
          offerId,
          barcode,
          error: err.message,
        });

        console.error(` ${barcode}: ${err.message}`);
      }

      await new Promise((r) => setTimeout(r, 200));
    }

    const report = {
      summary: {
        totalOzon: ozonProducts.length,
        updated,
        notFound,
        skipped,
        failed,
        timestamp: new Date().toISOString(),
      },
      updated: logUpdated,
      notFound: logNotFound,
      failed: logFailed,
    };

    const reportPath = path.join(__dirname, "ozon-update-report.json");

    fs.writeFileSync(reportPath, JSON.stringify(report, null, 2), "utf-8");

    console.log("\n Итог:");
    console.log(`   Всего с Ozon:        ${ozonProducts.length}`);
    console.log(`   Обновлено:           ${updated}`);
    console.log(`   Не найдено в Битриксе: ${notFound}`);
    console.log(`   Пропущено:           ${skipped}`);
    console.log(`   Ошибок:              ${failed}`);
    console.log(`\n Отчёт: ${reportPath}`);
  } catch (err) {
    console.error("Ошибка:", err.message);
  }
}

main();
