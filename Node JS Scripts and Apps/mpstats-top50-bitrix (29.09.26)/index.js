require("dotenv").config();

const fs = require("fs");
const BASE_BITRIX = process.env.BASE_BITRIX;
const MPSTATS_TOKEN = process.env.MPSTATS_TOKEN;
const ENTITY_TYPE_ID = 1070;

async function getAllItems(url) {
  try {
    const response = await fetch(url);
    const data = await response.json();

    console.log(`Всего карточек: ${data.total}`);
    console.log("Массив данных:");
    console.log(data.result.items);

    return data.result.items;
  } catch (error) {
    console.error("Ошибка:", error);
  }
}

async function updateItem(itemId, fields) {
  try {
    const response = await fetch(`${BASE_BITRIX}/crm.item.update.json`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        entityTypeId: ENTITY_TYPE_ID,
        id: itemId,
        fields: fields,
      }),
    });

    const data = await response.json();

    if (data.error) {
      console.error("Ошибка Битрикс24:", data.error_description);
      return null;
    }

    console.log(`Карточка ${itemId} обновлена:`);
    console.log(data.result.item);

    return data.result.item;
  } catch (error) {
    console.error("Ошибка запроса:", error);
    return null;
  }
}

async function addItem(fields) {
  try {
    const response = await fetch(`${BASE_BITRIX}/crm.item.add.json`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        entityTypeId: ENTITY_TYPE_ID,
        fields: fields,
      }),
    });

    const data = await response.json();

    if (data.error) {
      console.error("Ошибка Битрикс24:", data.error_description);
      return null;
    }

    console.log("Карточка создана:");
    console.log(data.result.item);

    return data.result.item;
  } catch (error) {
    console.error("Ошибка запроса:", error);
    return null;
  }
}

async function deleteItem(itemId) {
  try {
    const response = await fetch(`${BASE_BITRIX}/crm.item.delete.json`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        entityTypeId: ENTITY_TYPE_ID,
        id: itemId,
      }),
    });

    const data = await response.json();

    if (data.error) {
      console.error("Ошибка Битрикс24:", data.error_description);
      return null;
    }

    console.log(`Карточка ${itemId} удалена`);
    console.log(data.result);

    return data.result;
  } catch (error) {
    console.error("Ошибка запроса:", error);
    return null;
  }
}

function getLast14DaysRange() {
  const today = new Date();
  today.setDate(today.getDate() - 1);
  today.setDate(today.getDate() - 30);

  const past = new Date(today);
  past.setDate(past.getDate() - 13);
  const format = (d) => {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    return `${y}-${m}-${day}`;
  };

  return { d1: format(past), d2: format(today) };
}

function getYesterdayStr() {
  const d = new Date();
  d.setDate(d.getDate() - 1);
  d.setDate(d.getDate() - 30);

  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, "0");
  const day = String(d.getDate()).padStart(2, "0");

  return `${y}-${m}-${day}`;
}

const dateSuffix = getYesterdayStr();

async function getWbData({ d1, d2 }) {
  try {
    const response = await fetch(
      "https://mpstats.io/api/analytics/v1/wb/category/brands",
      {
        method: "POST",
        headers: {
          "X-Mpstats-TOKEN": MPSTATS_TOKEN,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          path: "Здоровье/БАДы",
          d1,
          d2,
        }),
      },
    );

    const data = await response.json();

    if (data.error || data.message || !data.data) {
      console.error(
        "Ошибка WB:",
        data.error_description || data.message || data,
      );
      return null;
    }

    const compact = data.data.map((brand) => ({
      name: brand.name,
      revenue: brand.revenue_estimated,
      sales: brand.sales,
    }));

    console.log(compact);
    fs.writeFileSync(
      `brands_wb_${dateSuffix}.json`,
      JSON.stringify(compact, null, 1),
    );

    return compact;
  } catch (error) {
    console.error("Ошибка запроса:", error);
    return null;
  }
}

async function getOzonData({ d1, d2 }) {
  try {
    const response = await fetch(
      "https://mpstats.io/api/analytics/v1/oz/category/brands",
      {
        method: "POST",
        headers: {
          "X-Mpstats-TOKEN": MPSTATS_TOKEN,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          path: "Аптека/Витамины и БАДы",
          d1,
          d2,
        }),
      },
    );

    const data = await response.json();

    if (data.error || data.message || !data.data) {
      console.error(
        "Ошибка WB:",
        data.error_description || data.message || data,
      );
      return null;
    }

    const compact = data.data.map((brand) => ({
      name: brand.name,
      revenue: brand.revenue,
      sales: brand.sales,
    }));

    console.log(compact);
    fs.writeFileSync(
      `brands_ozon_${dateSuffix}.json`,
      JSON.stringify(compact, null, 1),
    );

    return compact;
  } catch (error) {
    console.error("Ошибка запроса:", error);
    return null;
  }
}

function normalizeName(name) {
  return String(name || "")
    .replace(/[\s\u00A0\u200B\uFEFF]/g, "")
    .toLowerCase();
}

function mergeBrands(wbData, ozonData) {
  const map = new Map();

  for (const brand of wbData || []) {
    const key = normalizeName(brand.name);
    map.set(key, {
      name: brand.name,
      revenue_wb: brand.revenue || 0,
      revenue_ozon: 0,
      sales_wb: brand.sales || 0,
      sales_ozon: 0,
    });
  }

  for (const brand of ozonData || []) {
    const key = normalizeName(brand.name);
    if (map.has(key)) {
      const existing = map.get(key);
      existing.revenue_ozon = brand.revenue || 0;
      existing.sales_ozon = brand.sales || 0;
    } else {
      map.set(key, {
        name: brand.name,
        revenue_wb: 0,
        revenue_ozon: brand.revenue || 0,
        sales_wb: 0,
        sales_ozon: brand.sales || 0,
      });
    }
  }

  const result = [];
  for (const brand of map.values()) {
    const hasWb = brand.revenue_wb > 0;
    const hasOzon = brand.revenue_ozon > 0;

    let revenueAvg;
    if (hasWb && hasOzon) {
      revenueAvg = (brand.revenue_wb + brand.revenue_ozon) / 2;
    } else {
      revenueAvg = brand.revenue_wb + brand.revenue_ozon;
    }

    const hasWbSales = brand.sales_wb > 0;
    const hasOzonSales = brand.sales_ozon > 0;

    let salesAvg;
    if (hasWbSales && hasOzonSales) {
      salesAvg = Math.round((brand.sales_wb + brand.sales_ozon) / 2);
    } else {
      salesAvg = brand.sales_wb + brand.sales_ozon;
    }

    result.push({
      name: brand.name,
      revenue_wb: brand.revenue_wb,
      revenue_ozon: brand.revenue_ozon,
      sales_wb: brand.sales_wb,
      sales_ozon: brand.sales_ozon,
      sales_avg: salesAvg,
      revenue_avg: revenueAvg,
    });
  }

  result.sort((a, b) => b.revenue_avg - a.revenue_avg);

  return result.slice(0, 50);
}

async function mergedArray() {
  const wbDataBrands = await getWbData(getLast14DaysRange());
  const ozonDataBrands = await getOzonData(getLast14DaysRange());

  const merged = mergeBrands(wbDataBrands, ozonDataBrands);

  console.log(`Итого брендов: ${merged.length}`);
  console.log(merged);

  fs.writeFileSync(
    `brands_merged_${dateSuffix}.json`,
    JSON.stringify(merged, null, 1),
  );

  return merged;
}

async function syncTop50ToBitrix(mpstatsMergedArray) {
  const allItems = await getAllItems(
    `${BASE_BITRIX}/crm.item.list.json?entityTypeId=${ENTITY_TYPE_ID}`,
  );

  if (!allItems) {
    console.error("Не удалось получить карточки из Битрикс24");
    return;
  }

  const byTitle = new Map();
  for (const item of allItems) {
    if (item.title) {
      byTitle.set(normalizeName(item.title), item);
    }
  }

  const incomingTitles = new Set();

  for (const brand of mpstatsMergedArray || []) {
    const key = normalizeName(brand.name);
    incomingTitles.add(key);

    const fields = {
      title: brand.name,
      ufCrm_34_TOP50_WB_SALES_SUM: Math.round(brand.revenue_wb || 0),
      ufCrm_34_TOP50_OZON_SALES_SUM: Math.round(brand.revenue_ozon || 0),
      ufCrm_34_TOP50_WB_SALES_QUANTITY: brand.sales_wb || 0,
      ufCrm_34_TOP50_OZON_SALES_QUANTITY: brand.sales_ozon || 0,
      ufCrm_34_TOP50_QUANTITY_AVG: Math.round(brand.sales_avg || 0),
      ufCrm_34_TOP50_REVENUE_AVG: Math.round(brand.revenue_avg || 0),
    };

    const existing = byTitle.get(key);

    if (existing) {
      await updateItem(existing.id, fields);
    } else {
      await addItem(fields);
    }
  }

  for (const [key, item] of byTitle.entries()) {
    if (!incomingTitles.has(key)) {
      await deleteItem(item.id);
    }
  }

  console.log("Синхронизация Top-50 завершена");
}

(async () => {
  const mpstatsMergedArray = await mergedArray();
  await syncTop50ToBitrix(mpstatsMergedArray);
})();
