const fs = require("fs");
const path = require("path");

process.loadEnvFile();

async function fetchOnePage(categoryPath, opts = {}) {
  const {
    apiKey = process.env.MPSTATS_API_KEY,
    startRow = 0,
    endRow = 500,
    outFile = "mpstats_page.json",
  } = opts;

  if (!apiKey) {
    throw new Error("MPSTATS_API_KEY не найден");
  }

  const res = await fetch(
    "https://mpstats.io/api/analytics/v1/oz/category/items",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Mpstats-TOKEN": apiKey,
      },
      body: JSON.stringify({ path: categoryPath, startRow, endRow }),
    },
  );

  if (!res.ok) {
    const text = await res.text().catch(() => "");
    throw new Error(`MPSTATS ${res.status}: ${text.slice(0, 500)}`);
  }

  const json = await res.json();

  const items = (json.data || [])
    .filter((row) => (row.balance || 0) > 0 || (row.balance_fbs || 0) > 0)
    .map((row) => {
      const revenue = row.revenue ?? 0;
      const sales = row.sales ?? 0;
      const avg_price =
        sales > 0 ? Math.round((revenue / sales) * 100) / 100 : null;

      return {
        id: row.id,
        name: row.name,
        url: row.url,
        brand: row.brand,
        seller: row.seller,
        seller_id: row.seller_id,
        revenue,
        sales,
        avg_price,
      };
    });

  const output = {
    quantity: items.length,
    data: items,
  };

  const outPath = path.isAbsolute(outFile)
    ? outFile
    : path.join(__dirname, outFile);
  fs.writeFileSync(outPath, JSON.stringify(output, null, 2), "utf8");

  console.log(`Всего в категории: ${json.total}`);
  console.log(`Получено из MPSTATS: ${(json.data || []).length}`);
  console.log(`С остатками: ${items.length}`);
  console.log(`Сохранено в: ${outPath}`);

  return output;
}

fetchOnePage("Аптека/Витамины и БАДы", {
  startRow: 0,
  endRow: 500,
  outFile: "mpstats_page.json",
})
  .then((result) => {
    console.log("quantity:", result.quantity);
    console.log("Первые 3:", result.data.slice(0, 3));
  })
  .catch((err) => {
    console.error("Ошибка:", err.message);
  });
