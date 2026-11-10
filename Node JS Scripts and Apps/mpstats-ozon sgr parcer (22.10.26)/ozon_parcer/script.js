const puppeteer = require("puppeteer");
const fs = require("fs");
const path = require("path");

const EAEU_CODES = ["RU", "AM", "KZ", "BY", "KG"];

async function setupPage(page, opts = {}) {
  const { blockResources = true, aggressive = false } = opts;

  await page.setUserAgent(
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 " +
      "(KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36",
  );
  await page.setViewport({ width: 1366, height: 900 });
  await page.setExtraHTTPHeaders({
    "Accept-Language": "ru-RU,ru;q=0.9,en;q=0.8",
  });

  if (blockResources) {
    const blockedTypes = aggressive
      ? ["image", "font", "stylesheet", "media"]
      : ["image", "media"];

    await page.setRequestInterception(true);
    page.on("request", (req) => {
      if (blockedTypes.includes(req.resourceType())) {
        req.abort();
      } else {
        req.continue();
      }
    });
  }
}

async function parseSgrFromModal(url, opts = {}) {
  const { debug = false } = opts;

  const browser = await puppeteer.launch({
    headless: "new",
    args: [
      "--no-sandbox",
      "--disable-setuid-sandbox",
      "--disable-blink-features=AutomationControlled",
    ],
  });

  let totalBytes = 0;

  try {
    const page = await browser.newPage();

    page.on("response", (response) => {
      const headers = response.headers();
      if (headers["content-length"]) {
        totalBytes += parseInt(headers["content-length"], 10) || 0;
      }
    });

    await setupPage(page, { blockResources: true, aggressive: true });

    await page.goto(url, { waitUntil: "networkidle2", timeout: 60000 });

    await page
      .waitForFunction(
        () => {
          return Array.from(document.querySelectorAll("div[title]")).length > 0;
        },
        { timeout: 15000 },
      )
      .catch(() => {});

    const sgrNumber = await page.evaluate((eaeuCodes) => {
      const divs = Array.from(document.querySelectorAll("div[title]"));
      for (const div of divs) {
        const title = (div.getAttribute("title") || "").trim();
        if (!title) continue;
        for (const code of eaeuCodes) {
          if (title.startsWith(code + ".")) return title;
        }
      }
      return null;
    }, EAEU_CODES);

    if (debug) {
      console.log(`[DEBUG] Modal URL: ${url}`);
      console.log(`[DEBUG] Modal SGR: ${JSON.stringify(sgrNumber)}`);
      console.log(
        `[DEBUG] Трафик модалки: ${(totalBytes / 1024 / 1024).toFixed(2)} МБ`,
      );
    }

    return { sgrNumber, bytes: totalBytes };
  } finally {
    await browser.close();
  }
}

async function parseSgrFromCard(productId, opts = {}) {
  const { debug = false } = opts;
  const url = `https://ozon.ru/context/detail/id/${productId}`;

  const browser = await puppeteer.launch({
    headless: "new",
    args: [
      "--no-sandbox",
      "--disable-setuid-sandbox",
      "--disable-blink-features=AutomationControlled",
    ],
  });

  let totalBytes = 0;

  try {
    const page = await browser.newPage();

    page.on("response", (response) => {
      const headers = response.headers();
      if (headers["content-length"]) {
        totalBytes += parseInt(headers["content-length"], 10) || 0;
      }
    });

    await setupPage(page, { blockResources: true, aggressive: false });

    if (debug) console.log(`[DEBUG] Card goto: ${url}`);
    await page.goto(url, { waitUntil: "networkidle2", timeout: 60000 });

    await page
      .waitForFunction(() => window.location.pathname.startsWith("/product/"), {
        timeout: 20000,
      })
      .catch(() => {});

    if (debug) console.log(`[DEBUG] Card final URL: ${page.url()}`);

    await page.evaluate(async () => {
      for (let i = 0; i < 10; i++) {
        window.scrollBy(0, 500);
        await new Promise((r) => setTimeout(r, 300));
      }
    });
    await new Promise((r) => setTimeout(r, 2000));

    await page
      .waitForFunction(
        (eaeuCodes) => {
          const normalize = (str) => {
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
            return (str || "")
              .replace(/[А-Я]/g, (ch) => map[ch] || ch)
              .toUpperCase();
          };

          const dts = document.querySelectorAll("dt");
          for (const dt of dts) {
            const t = (dt.textContent || "").trim();
            if (t !== "Номер СГР" && !t.startsWith("Номер СГР")) continue;

            const dl = dt.parentElement;
            const dd = dl ? dl.querySelector("dd") : dt.nextElementSibling;
            if (!dd) continue;

            const value = normalize((dd.textContent || "").trim());
            if (eaeuCodes.some((code) => value.startsWith(code + "."))) {
              return true;
            }
          }
          return false;
        },
        { timeout: 20000 },
        EAEU_CODES,
      )
      .catch(() => {});

    await new Promise((r) => setTimeout(r, 1000));

    const sgrNumber = await page.evaluate((eaeuCodes) => {
      const normalize = (str) => {
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
        return (str || "")
          .replace(/[А-Я]/g, (ch) => map[ch] || ch)
          .toUpperCase();
      };

      const startsWithEaeu = (str) => {
        const t = normalize(str).trim();
        return eaeuCodes.some((code) => t.startsWith(code + "."));
      };

      const dts = document.querySelectorAll("dt");
      for (const dt of dts) {
        const t = (dt.textContent || "").trim();
        if (t === "Номер СГР" || t.startsWith("Номер СГР")) {
          const dl = dt.parentElement;
          if (dl) {
            const dd = dl.querySelector("dd");
            if (dd) {
              const value = (dd.textContent || "").trim();
              if (startsWithEaeu(value)) return value;
            }
          }
          const nextDd = dt.nextElementSibling;
          if (nextDd && nextDd.tagName === "DD") {
            const value = (nextDd.textContent || "").trim();
            if (startsWithEaeu(value)) return value;
          }
        }
      }

      const all = document.querySelectorAll("*");
      for (const el of all) {
        if (el.children.length > 0) continue;
        const t = (el.textContent || "").trim();
        if (t !== "Номер СГР" && !t.startsWith("Номер СГР")) continue;

        const container = el.parentElement;
        if (!container) continue;

        const valueContainer = container.nextElementSibling;
        if (!valueContainer) continue;

        const candidates = valueContainer.querySelectorAll("*");
        for (const c of candidates) {
          if (c.children.length > 0) continue;
          const v = (c.textContent || "").trim();
          if (startsWithEaeu(v)) return v;
        }

        const direct = (valueContainer.textContent || "").trim();
        if (startsWithEaeu(direct)) return direct;
      }

      return null;
    }, EAEU_CODES);

    if (debug) {
      console.log(`[DEBUG] Card SGR: ${JSON.stringify(sgrNumber)}`);
      console.log(
        `[DEBUG] Трафик карточки: ${(totalBytes / 1024 / 1024).toFixed(2)} МБ`,
      );
    }

    return { sgrNumber, bytes: totalBytes };
  } finally {
    await browser.close();
  }
}

async function main() {
  const inputPath = path.join(__dirname, "..", "mpstats_page.json");

  if (!fs.existsSync(inputPath)) {
    throw new Error(`Файл не найден: ${inputPath}`);
  }

  const parsed = JSON.parse(fs.readFileSync(inputPath, "utf8"));
  const items = Array.isArray(parsed) ? parsed : parsed.data || [];

  console.log(`Загружено товаров: ${items.length}`);

  const results = [];
  const bytesPerIteration = [];
  let totalBytesSoFar = 0;
  let modalCount = 0;
  let cardCount = 0;

  for (let i = 0; i < items.length; i++) {
    const item = items[i];
    const modalUrl = `https://www.ozon.ru/modal/product_checked/?product_id=${item.id}`;

    console.log(
      `[${i + 1}/${items.length}] ${item.id} - ${item.name?.slice(0, 40)}...`,
    );

    let sgrNumber = null;

    try {
      const { sgrNumber: sgr, bytes } = await parseSgrFromModal(modalUrl);
      sgrNumber = sgr;
      bytesPerIteration.push(bytes);
      totalBytesSoFar += bytes;
      modalCount++;

      const mb = (bytes / 1024 / 1024).toFixed(2);
      const totalMb = (totalBytesSoFar / 1024 / 1024).toFixed(2);

      if (sgrNumber) {
        console.log(`  ✓ SGR (modal): ${sgrNumber} | ${mb} МБ`);
      } else {
        console.log(`  - SGR не найден в модалке | ${mb} МБ`);
      }
      console.log(`  ∑ всего: ${totalMb} МБ`);
    } catch (err) {
      console.error(`  ✗ Ошибка модалки: ${err.message}`);
    }

    if (!sgrNumber) {
      try {
        console.log(`  → Fallback: карточка товара...`);
        const { sgrNumber: sgr, bytes } = await parseSgrFromCard(item.id);
        sgrNumber = sgr;
        bytesPerIteration.push(bytes);
        totalBytesSoFar += bytes;
        cardCount++;

        const mb = (bytes / 1024 / 1024).toFixed(2);
        const totalMb = (totalBytesSoFar / 1024 / 1024).toFixed(2);

        if (sgrNumber) {
          console.log(`  ✓ SGR (card): ${sgrNumber} | ${mb} МБ`);
        } else {
          console.log(`  - SGR не найден и в карточке | ${mb} МБ`);
        }
        console.log(`  ∑ всего: ${totalMb} МБ`);
      } catch (err) {
        console.error(`  ✗ Ошибка карточки: ${err.message}`);
      }
    }

    results.push({
      ...item,
      sgr_number: sgrNumber,
    });

    if (i < items.length - 1) {
      const delay = 3000 + Math.random() * 3000;
      await new Promise((r) => setTimeout(r, delay));
    }
  }

  const output = {
    quantity: results.length,
    data: results,
  };

  fs.writeFileSync(inputPath, JSON.stringify(output, null, 2), "utf8");
  console.log(`\nГотово. Сохранено в ${inputPath}`);

  const withSgr = results.filter((r) => r.sgr_number).length;
  console.log(`С СГР: ${withSgr} / ${results.length}`);

  if (bytesPerIteration.length > 0) {
    const totalMb = totalBytesSoFar / 1024 / 1024;
    const avgMb = totalMb / bytesPerIteration.length;
    console.log(`\n--- Трафик ---`);
    console.log(`Всего: ${totalMb.toFixed(2)} МБ`);
    console.log(`Модалок: ${modalCount}, карточек (fallback): ${cardCount}`);
    console.log(`Среднее на итерацию: ${avgMb.toFixed(2)} МБ`);
    console.log(
      `Прогноз на 134 000 товаров: ${((avgMb * 134000) / 1024).toFixed(1)} ГБ`,
    );
  }
}

main().catch((err) => {
  console.error("Критическая ошибка:", err);
  process.exit(1);
});
