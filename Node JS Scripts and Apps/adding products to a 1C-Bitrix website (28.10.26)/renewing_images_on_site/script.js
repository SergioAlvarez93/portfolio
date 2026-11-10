const fs = require("fs");
const path = require("path");

const ENV_PATH = path.join(__dirname, "..", ".env");

if (fs.existsSync(ENV_PATH)) {
  process.loadEnvFile(ENV_PATH);
}

const WEBHOOK_URL = process.env.BITRIX_WEBHOOK_URL;
const ROOT_PATH = process.env.SERVER_IMAGES_ROOT;

async function safeFetch(url, options = {}, maxRetries = 3) {
  for (let attempt = 1; attempt <= maxRetries; attempt++) {
    try {
      const response = await fetch(url, options);

      if (!response.ok) {
        if (
          attempt < maxRetries &&
          (response.status >= 500 || response.status === 429)
        ) {
          await new Promise((r) => setTimeout(r, 1000 * attempt));
          continue;
        }

        const text = await response.text();
        throw new Error(`HTTP ${response.status}: ${text.slice(0, 200)}`);
      }

      return await response.json();
    } catch (err) {
      if (attempt === maxRetries) throw err;
      await new Promise((r) => setTimeout(r, 1000 * attempt));
    }
  }
}

async function fetchJson(url, options = {}) {
  const response = await fetch(url, options);

  if (!response.ok) {
    const text = await response.text();
    throw new Error(`HTTP ${response.status}: ${text.slice(0, 200)}`);
  }

  return await response.json();
}

function writeJsonStream(filePath, array) {
  return new Promise((resolve, reject) => {
    const stream = fs.createWriteStream(filePath);
    stream.on("error", reject);
    stream.on("finish", resolve);

    stream.write("[\n");
    array.forEach((item, i) => {
      if (i > 0) stream.write(",\n");
      stream.write(JSON.stringify(item));
    });
    stream.write("\n]");
    stream.end();
  });
}

async function getServerImagesData(rootPath) {
  if (!rootPath) throw new Error("Не передан rootPath");
  if (!fs.existsSync(rootPath)) {
    throw new Error(`Путь не найден: ${rootPath}`);
  }

  const result = [];
  const entries = fs.readdirSync(rootPath, { withFileTypes: true });

  for (const entry of entries) {
    if (!entry.isDirectory()) continue;

    const folderName = entry.name;
    const barcodeMatch = folderName.match(/(\d{13})$/);
    if (!barcodeMatch) continue;

    const barcode = barcodeMatch[1];
    const folderPath = path.join(rootPath, folderName);

    const sitePath = path.join(folderPath, "site");
    if (!fs.existsSync(sitePath) || !fs.statSync(sitePath).isDirectory()) {
      console.warn(`Нет папки site: ${folderName}`);
      continue;
    }

    const imageFiles = fs
      .readdirSync(sitePath, { withFileTypes: true })
      .filter((f) => f.isFile())
      .filter((f) => /\.(jpg|jpeg|png|webp|gif)$/i.test(f.name))
      .map((f) => f.name);

    if (imageFiles.length === 0) {
      console.warn(`Нет картинок в site: ${folderName}`);
      continue;
    }

    imageFiles.sort((a, b) => {
      const numA = parseInt(a.match(/^(\d+)/)?.[1] ?? "0", 10);
      const numB = parseInt(b.match(/^(\d+)/)?.[1] ?? "0", 10);
      return numA - numB;
    });

    const images = [];

    for (const fileName of imageFiles) {
      const filePath = path.join(sitePath, fileName);
      const buffer = fs.readFileSync(filePath);

      images.push({
        fileName: fileName,
        base64: buffer.toString("base64"),
      });
    }

    result.push({ barcode, images });
    console.log(`${folderName} → ${images.length} картинок`);
  }

  console.log(`Всего товаров с картинками: ${result.length}`);
  return result;
}

async function getAllBitrixData(iblockId, selectFields) {
  if (!iblockId) throw new Error("Не передан iblockId");

  if (!Array.isArray(selectFields) || selectFields.length === 0) {
    throw new Error("selectFields должен быть непустым массивом");
  }

  let allProducts = [];
  let start = 0;
  let hasMore = true;
  let page = 1;

  while (hasMore) {
    const params = new URLSearchParams();

    selectFields.forEach((field, index) => {
      params.append(`select[${index}]`, field);
    });

    params.append("filter[iblockId]", iblockId);
    params.append("start", start);

    const url = `${WEBHOOK_URL}/catalog.product.list.json?${params.toString()}`;
    console.log(`Страница ${page}, start=${start}...`);

    const result = await fetchJson(url);

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

  const transformed = allProducts.map((product) => {
    const imagesIds = [];

    if (product.previewPicture?.id) {
      imagesIds.push(String(product.previewPicture.id));
    }

    if (product.detailPicture?.id) {
      imagesIds.push(String(product.detailPicture.id));
    }

    if (Array.isArray(product.property257)) {
      for (const item of product.property257) {
        if (item?.value?.id) {
          imagesIds.push(String(item.value.id));
        }
      }
    }

    return {
      id: product.id,
      name: product.name,
      barcode: product.property261?.value ?? null,
      imagesIds: imagesIds,
    };
  });

  console.log(`Преобразовано товаров: ${transformed.length}`);
  return transformed;
}

function mergeBitrixWithServerData(bitrixData, serverData) {
  if (!Array.isArray(bitrixData)) {
    throw new Error("bitrixData должен быть массивом");
  }

  if (!Array.isArray(serverData)) {
    throw new Error("serverData должен быть массивом");
  }

  const serverMap = new Map();

  for (const item of serverData) {
    if (item.barcode) {
      serverMap.set(String(item.barcode), item);
    }
  }

  const merged = [];

  for (const product of bitrixData) {
    const barcode = product.barcode ? String(product.barcode) : null;
    if (!barcode) continue;

    const serverItem = serverMap.get(barcode);

    if (
      !serverItem ||
      !Array.isArray(serverItem.images) ||
      serverItem.images.length === 0
    ) {
      continue;
    }

    merged.push({
      id: product.id,
      barcode: barcode,
      imagesIds: product.imagesIds ?? [],
      images: serverItem.images,
    });
  }

  console.log(`Товаров в Битриксе: ${bitrixData.length}`);
  console.log(`Товаров на сервере: ${serverData.length}`);
  console.log(`Совпало и готово к обновлению: ${merged.length}`);

  return merged;
}

async function deleteCurrentBitrixItemImages(productId, imagesIds) {
  for (const imageId of imagesIds) {
    const body = {
      productId: productId,
      id: imageId,
    };

    const result = await safeFetch(
      `${WEBHOOK_URL}/catalog.productImage.delete.json`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(body),
      },
    );

    if (result.error) {
      throw new Error(`Битрикс вернул ошибку: ${result.error_description}`);
    }

    console.log(`Изображение ${imageId} для продукта ${productId} удалено!`);

    await new Promise((r) => setTimeout(r, 200));
  }
}

async function uploadLocalImage(productId, base64String, fileName, imageType) {
  if (!productId) throw new Error("Не передан productId");
  if (!base64String) throw new Error("Не передан base64String");
  if (!fileName) throw new Error("Не передан fileName");
  if (!imageType) throw new Error("Не передан imageType");

  const allowedTypes = ["PREVIEW_PICTURE", "DETAIL_PICTURE", "MORE_PHOTO"];

  if (!allowedTypes.includes(imageType)) {
    throw new Error(`Недопустимый imageType: "${imageType}"`);
  }

  const body = {
    fields: {
      productId: productId,
      type: imageType,
    },
    fileContent: [fileName, base64String],
  };

  const result = await safeFetch(
    `${WEBHOOK_URL}/catalog.productImage.add.json`,
    {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(body),
    },
  );

  if (result.error) {
    throw new Error(`Битрикс вернул ошибку: ${result.error_description}`);
  }

  return result;
}

async function processProduct(item) {
  if (item.imagesIds && item.imagesIds.length > 0) {
    await deleteCurrentBitrixItemImages(item.id, item.imagesIds);
  }

  for (let i = 0; i < item.images.length; i++) {
    const { fileName, base64 } = item.images[i];

    if (i === 0) {
      for (const type of ["PREVIEW_PICTURE", "DETAIL_PICTURE"]) {
        try {
          const result = await uploadLocalImage(
            item.id,
            base64,
            fileName,
            type,
          );

          console.log(
            `Товар ${item.id}, ${fileName} (${type}): ${result.result?.productImage?.id}`,
          );
        } catch (err) {
          console.error(
            `Товар ${item.id}, ${fileName} (${type}): ${err.message}`,
          );
        }

        await new Promise((r) => setTimeout(r, 200));
      }
    } else {
      try {
        const result = await uploadLocalImage(
          item.id,
          base64,
          fileName,
          "MORE_PHOTO",
        );

        console.log(
          `Товар ${item.id}, ${fileName} (MORE_PHOTO): ${result.result?.productImage?.id}`,
        );
      } catch (err) {
        console.error(
          `Товар ${item.id}, ${fileName} (MORE_PHOTO): ${err.message}`,
        );
      }

      await new Promise((r) => setTimeout(r, 200));
    }
  }
}

async function main() {
  try {
    if (!WEBHOOK_URL) {
      throw new Error("Не задан BITRIX_WEBHOOK_URL в корневом файле .env");
    }

    if (!ROOT_PATH) {
      throw new Error("Не задан SERVER_IMAGES_ROOT в корневом файле .env");
    }

    const selectFields = [
      "id",
      "iblockId",
      "name",
      "previewPicture",
      "detailPicture",
      "property261",
      "property257",
    ];

    const bitrixData = await getAllBitrixData(12, selectFields);
    const serverData = await getServerImagesData(ROOT_PATH);
    const mergedArr = mergeBitrixWithServerData(bitrixData, serverData);

    console.log(`\nВсего товаров к обработке: ${mergedArr.length}`);

    for (const item of mergedArr) {
      try {
        await processProduct(item);
      } catch (err) {
        console.error(`Товар ${item.id} (${item.barcode}): ${err.message}`);
      }
    }

    console.log("\nОбработка завершена");
  } catch (err) {
    console.error("Критическая ошибка:", err.message);
  }
}

main();
