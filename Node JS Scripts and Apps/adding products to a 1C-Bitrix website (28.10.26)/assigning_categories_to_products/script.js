require("dotenv").config();

const fs = require("fs");
const path = require("path");
const { fetch } = require("wreq-js");
const { askAIForCategories } = require("./ai-matcher.js");

const WEBHOOK_URL = process.env.BITRIX_WEBHOOK_URL;

async function getSectionsTree(iblockId, outputFile = "sections-tree.json") {
  if (!iblockId) throw new Error("Не передан iblockId");

  let allSections = [];
  let start = 0;
  let hasMore = true;
  let page = 1;

  while (hasMore) {
    const params = new URLSearchParams();

    params.append("filter[iblockId]", iblockId);
    params.append("select[0]", "id");
    params.append("select[1]", "name");
    params.append("select[2]", "iblockSectionId");
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.section.list.json?${params.toString()}`;

    console.log(`Страница ${page}, start=${start}...`);

    const response = await fetch(url);
    const result = await response.json();

    if (result.error) {
      throw new Error(`Битрикс вернул ошибку: ${result.error_description}`);
    }

    const sections = result.result?.sections ?? [];

    allSections = allSections.concat(sections);

    console.log(
      `   Получено: ${sections.length}, накоплено: ${allSections.length} из ${result.total ?? "?"}`,
    );

    if (result.next) {
      start = result.next;
      page++;

      await new Promise((r) => setTimeout(r, 300));
    } else {
      hasMore = false;
    }
  }

  const byId = new Map();

  for (const section of allSections) {
    byId.set(section.id, {
      id: section.id,
      name: section.name,
      parentId: section.iblockSectionId,
      children: [],
    });
  }

  const roots = [];

  for (const node of byId.values()) {
    if (node.parentId && byId.has(node.parentId)) {
      byId.get(node.parentId).children.push(node);
    } else {
      roots.push(node);
    }
  }

  function formatNode(node) {
    if (node.children.length === 0) {
      return {
        iblockSectionId: node.id,
        name: node.name,
      };
    }

    return {
      [node.name]: node.children.map(formatNode),
    };
  }

  const tree = roots.filter((root) => root.children.length > 0).map(formatNode);

  const outputPath = path.join(__dirname, outputFile);

  fs.writeFileSync(outputPath, JSON.stringify(tree, null, 2), "utf-8");

  console.log(`\nСохранено: ${outputPath}`);

  return tree;
}

async function getProductsForSectionMatch(
  iblockId,
  outputFile = "products-sections.json",
) {
  if (!iblockId) throw new Error("Не передан iblockId");

  let allProducts = [];
  let start = 0;
  let hasMore = true;
  let page = 1;

  while (hasMore) {
    const params = new URLSearchParams();

    params.append("select[0]", "iblockId");
    params.append("select[1]", "id");
    params.append("select[2]", "name");
    params.append("select[3]", "iblockSection");
    params.append("select[4]", "iblockSectionId");
    params.append("select[5]", "property73");
    params.append("filter[iblockId]", iblockId);
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

  const transformed = allProducts.map((product) => ({
    id: product.id,
    name: product.name,
    iblockSectionId: product.iblockSectionId,
    iblockSection: product.iblockSection ?? [],
    property73: product.property73?.[0]?.valueEnum ?? null,
  }));

  const outputPath = path.join(__dirname, outputFile);

  fs.writeFileSync(outputPath, JSON.stringify(transformed, null, 2), "utf-8");

  console.log(`\nСохранено: ${outputPath}`);
  console.log(`Всего товаров: ${transformed.length}`);

  return transformed;
}

async function updateProductSection(productId, iblockSectionId, iblockSection) {
  if (!productId) throw new Error("Не передан productId");
  if (!iblockSectionId) throw new Error("Не передан iblockSectionId");

  const body = {
    id: productId,
    fields: {
      iblockSectionId: iblockSectionId,
      iblockSection: iblockSection,
    },
  };

  const response = await fetch(`${WEBHOOK_URL}/catalog.product.update.json`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(body),
  });

  const result = await response.json();

  if (result.error) {
    throw new Error(`Битрикс вернул ошибку: ${result.error_description}`);
  }

  return result;
}

async function main() {
  try {
    if (!WEBHOOK_URL) {
      throw new Error("Не задан BITRIX_WEBHOOK_URL в файле .env");
    }

    const tree = await getSectionsTree(11, "sections-tree.json");

    const products = await getProductsForSectionMatch(
      11,
      "products-sections.json",
    );

    const aiMatches = await askAIForCategories(tree, products);

    console.log(`\nИИ вернул ${aiMatches.length} сопоставлений`);

    const outputPath = path.join(__dirname, "ai-matched.json");

    fs.writeFileSync(outputPath, JSON.stringify(aiMatches, null, 2), "utf-8");

    console.log(`\nИтог сохранён: ${outputPath}`);

    let success = 0;
    let failed = 0;

    for (const item of aiMatches) {
      try {
        await updateProductSection(
          item.id,
          item.iblockSectionId,
          item.iblockSection,
        );

        success++;

        console.log(
          `Товар ${item.id}: обновлён (разделы: ${item.iblockSection.join(", ")})`,
        );
      } catch (err) {
        failed++;

        console.error(`Товар ${item.id}: ${err.message}`);
      }

      await new Promise((r) => setTimeout(r, 200));
    }

    console.log(`\nИтог: успешно ${success}, ошибок ${failed}`);
  } catch (err) {
    console.error("Ошибка:", err.message);
  }
}

main();
