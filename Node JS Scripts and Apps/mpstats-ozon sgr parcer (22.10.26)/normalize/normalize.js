const fs = require("fs");
const path = require("path");
const ExcelJS = require("exceljs");

const INPUT_PATH = path.join(__dirname, "..", "mpstats_page.json");
const OUTPUT_JSON_PATH = path.join(__dirname, "..", "mpstats_grouped.json");
const OUTPUT_XLSX_PATH = path.join(__dirname, "..", "mpstats_report.xlsx");

const EAEU_CODES = ["RU", "AM", "KZ", "BY", "KG"];

function normalizeCyrillic(str) {
  const map = {
    А: "A",
    В: "B",
    Е: "E",
    К: "K",
    М: "M",
    Н: "H",
    О: "O",
    Р: "P",
    С: "C",
    Т: "T",
    У: "Y",
    Х: "X",
  };

  return (str || "").replace(/[А-Я]/g, (ch) => map[ch] || ch);
}

function normalizeSgr(raw) {
  if (!raw || typeof raw !== "string") return null;

  const trimmed = raw.trim();

  const match = trimmed.match(
    /([A-ZА-Я]{2}\.\d{2}\.[0-9A-ZА-Я.]+?\d)(?:\s|$|[^\dA-ZА-Я.])/i,
  );

  let candidate = match ? match[1] : trimmed;
  candidate = candidate.replace(/[^\dA-ZА-Я.]+$/i, "").trim();
  candidate = candidate.replace(/\.+$/, "");

  if (!candidate) return null;

  const normalized = normalizeCyrillic(candidate);

  const upper = normalized.toUpperCase();

  if (!EAEU_CODES.some((code) => upper.startsWith(code + "."))) {
    return normalized;
  }

  return normalized;
}

function readInput() {
  if (!fs.existsSync(INPUT_PATH)) {
    throw new Error(`Файл не найден: ${INPUT_PATH}`);
  }

  const parsed = JSON.parse(fs.readFileSync(INPUT_PATH, "utf8"));
  const items = Array.isArray(parsed) ? parsed : parsed.data || [];

  return items;
}

function normalizeAllSgr(items) {
  let changed = 0;
  let nulls = 0;

  for (const item of items) {
    const original = item.sgr_number;
    const normalized = normalizeSgr(original);

    if (!normalized) {
      nulls++;
      item.sgr_number = null;
      continue;
    }

    if (normalized !== original) {
      changed++;
    }

    item.sgr_number = normalized;
  }

  return { changed, nulls, total: items.length };
}

function groupBySeller(items) {
  const groups = new Map();

  for (const item of items) {
    const key = String(item.seller_id || "unknown");

    if (!groups.has(key)) {
      groups.set(key, {
        seller_id: item.seller_id ?? null,
        seller: item.seller || "Неизвестный продавец",
        items: [],
        total_revenue: 0,
        total_sales: 0,
      });
    }

    const group = groups.get(key);

    const revenue = Number(item.revenue) || 0;
    const sales = Number(item.sales) || 0;

    group.items.push({
      id: item.id,
      name: item.name,
      url: item.url,
      brand: item.brand,
      sgr_number: item.sgr_number,
      revenue,
      sales,
      avg_price: item.avg_price ?? null,
    });

    group.total_revenue += revenue;
    group.total_sales += sales;
  }

  const data = Array.from(groups.values()).sort(
    (a, b) => b.items.length - a.items.length,
  );

  return {
    quantity: items.length,
    data,
  };
}

async function generateExcel(grouped) {
  const workbook = new ExcelJS.Workbook();

  const summarySheet = workbook.addWorksheet("Сводка");

  summarySheet.columns = [
    { header: "Seller ID", key: "seller_id", width: 15 },
    { header: "Продавец", key: "seller", width: 40 },
    { header: "Товаров", key: "total", width: 12 },
    { header: "С СГР", key: "with_sgr", width: 10 },
    { header: "Без СГР", key: "without_sgr", width: 10 },
    { header: "Сумма продаж (₽)", key: "total_revenue", width: 20 },
    { header: "Кол-во продаж", key: "total_sales", width: 15 },
    { header: "Средняя цена (₽)", key: "avg_price", width: 18 },
  ];

  for (const group of grouped.data) {
    const withSgr = group.items.filter((it) => it.sgr_number).length;
    const withoutSgr = group.items.length - withSgr;

    const avgPrice =
      group.total_sales > 0
        ? Math.round((group.total_revenue / group.total_sales) * 100) / 100
        : null;

    summarySheet.addRow({
      seller_id: group.seller_id,
      seller: group.seller,
      total: group.items.length,
      with_sgr: withSgr,
      without_sgr: withoutSgr,
      total_revenue: group.total_revenue,
      total_sales: group.total_sales,
      avg_price: avgPrice,
    });
  }

  summarySheet.getRow(1).font = { bold: true };
  summarySheet.getRow(1).fill = {
    type: "pattern",
    pattern: "solid",
    fgColor: { argb: "FFD9E1F2" },
  };

  summarySheet.autoFilter = {
    from: { row: 1, column: 1 },
    to: { row: 1, column: 8 },
  };

  summarySheet.views = [{ state: "frozen", ySplit: 1 }];

  summarySheet.getColumn("total_revenue").numFmt = "#,##0";
  summarySheet.getColumn("total_sales").numFmt = "#,##0";
  summarySheet.getColumn("avg_price").numFmt = "#,##0.00";

  const itemsSheet = workbook.addWorksheet("Товары");

  itemsSheet.columns = [
    { header: "Seller ID", key: "seller_id", width: 15 },
    { header: "Продавец", key: "seller", width: 35 },
    { header: "ID товара", key: "id", width: 15 },
    { header: "Название", key: "name", width: 60 },
    { header: "Бренд", key: "brand", width: 25 },
    { header: "Номер СГР", key: "sgr_number", width: 35 },
    { header: "Сумма продаж (₽)", key: "revenue", width: 18 },
    { header: "Кол-во продаж", key: "sales", width: 14 },
    { header: "Средняя цена (₽)", key: "avg_price", width: 16 },
    { header: "URL", key: "url", width: 60 },
  ];

  for (const group of grouped.data) {
    for (const item of group.items) {
      itemsSheet.addRow({
        seller_id: group.seller_id,
        seller: group.seller,
        id: item.id,
        name: item.name,
        brand: item.brand,
        sgr_number: item.sgr_number || "",
        revenue: item.revenue ?? 0,
        sales: item.sales ?? 0,
        avg_price: item.avg_price ?? null,
        url: item.url,
      });
    }
  }

  itemsSheet.getRow(1).font = { bold: true };
  itemsSheet.getRow(1).fill = {
    type: "pattern",
    pattern: "solid",
    fgColor: { argb: "FFD9E1F2" },
  };

  itemsSheet.autoFilter = {
    from: { row: 1, column: 1 },
    to: { row: 1, column: 10 },
  };

  itemsSheet.views = [{ state: "frozen", ySplit: 1 }];

  itemsSheet.getColumn("revenue").numFmt = "#,##0";
  itemsSheet.getColumn("sales").numFmt = "#,##0";
  itemsSheet.getColumn("avg_price").numFmt = "#,##0.00";

  itemsSheet.getColumn("url").eachCell((cell, rowNumber) => {
    if (rowNumber === 1) return;

    const value = cell.value;

    if (value && typeof value === "string" && value.startsWith("http")) {
      cell.value = {
        text: value,
        hyperlink: value,
      };

      cell.font = { color: { argb: "FF0563C1" }, underline: true };
    }
  });

  await workbook.xlsx.writeFile(OUTPUT_XLSX_PATH);

  console.log(`Excel-отчёт сохранён: ${OUTPUT_XLSX_PATH}`);
}

async function main() {
  console.log(`Читаю: ${INPUT_PATH}`);

  const items = readInput();

  console.log(`Загружено товаров: ${items.length}`);

  console.log(`\n--- Нормализация sgr_number ---`);

  const { changed, nulls, total } = normalizeAllSgr(items);

  console.log(`Всего товаров: ${total}`);
  console.log(`Изменено: ${changed}`);
  console.log(`Осталось null: ${nulls}`);

  console.log(`\n--- Группировка по seller_id ---`);

  const grouped = groupBySeller(items);

  console.log(`Уникальных селлеров: ${grouped.data.length}`);

  fs.writeFileSync(OUTPUT_JSON_PATH, JSON.stringify(grouped, null, 2), "utf8");

  console.log(`Сгруппированный JSON сохранён: ${OUTPUT_JSON_PATH}`);

  console.log(`\n--- Генерация Excel ---`);

  await generateExcel(grouped);

  console.log(`\n--- Итог ---`);

  console.log(`Товаров: ${grouped.quantity}`);
  console.log(`Селлеров: ${grouped.data.length}`);
  console.log(`С СГР: ${items.filter((it) => it.sgr_number).length}`);
  console.log(`Без СГР: ${items.filter((it) => !it.sgr_number).length}`);

  const totalRevenue = items.reduce(
    (s, it) => s + (Number(it.revenue) || 0),
    0,
  );

  const totalSales = items.reduce((s, it) => s + (Number(it.sales) || 0), 0);

  console.log(`Суммарная выручка: ${totalRevenue.toLocaleString("ru-RU")} ₽`);
  console.log(`Суммарные продажи: ${totalSales.toLocaleString("ru-RU")} шт`);
}

main().catch((err) => {
  console.error("Критическая ошибка:", err);
  process.exit(1);
});
