const puppeteer = require("puppeteer");
const fs = require("fs");
const path = require("path");

const EAEU_CODES = ["RU", "AM", "KZ", "BY", "KG"];

async function setupPage(page) {
  await page.setUserAgent(
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 " +
      "(KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36",
  );
  await page.setViewport({ width: 1366, height: 900 });
  await page.setExtraHTTPHeaders({
    "Accept-Language": "ru-RU,ru;q=0.9,en;q=0.8",
  });

  await page.setRequestInterception(true);
  page.on("request", (req) => {
    if (["image", "media"].includes(req.resourceType())) {
      req.abort();
    } else {
      req.continue();
    }
  });
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

    await setupPage(page);

    if (debug) console.log(`[DEBUG] goto: ${url}`);
    await page.goto(url, { waitUntil: "networkidle2", timeout: 60000 });

    await page
      .waitForFunction(() => window.location.pathname.startsWith("/product/"), {
        timeout: 20000,
      })
      .catch(() => {});

    if (debug) console.log(`[DEBUG] final URL: ${page.url()}`);

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
      console.log(`[DEBUG] SGR: ${JSON.stringify(sgrNumber)}`);
      console.log(
        `[DEBUG] Трафик: ${(totalBytes / 1024 / 1024).toFixed(2)} МБ`,
      );
    }

    return { sgrNumber, bytes: totalBytes };
  } finally {
    await browser.close();
  }
}

function extractProductId(url) {
  let m = url.match(/\/context\/detail\/id\/(\d+)/);
  if (m) return m[1];

  m = url.match(/\/product\/(?:[^/?#]*-)?(\d+)(?:[/?#]|$)/);
  if (m) return m[1];

  return null;
}

async function main() {
  const inputPath = path.join(__dirname, "..", "mpstats_page.json");

  if (!fs.existsSync(inputPath)) {
    throw new Error(`Файл не найден: ${inputPath}`);
  }

  const parsed = JSON.parse(fs.readFileSync(inputPath, "utf8"));
  const items = Array.isArray(parsed) ? parsed : parsed.data || [];

  console.log(`Загружено товаров: ${items.length}`);

  const targets = items.filter(
    (it) => !it.sgr_number || it.sgr_number === null,
  );

  console.log(`Товаров с sgr_number = null: ${targets.length}`);

  if (targets.length === 0) {
    console.log("Нечего обрабатывать. Выход.");
    return;
  }

  let updated = 0;
  let stillNull = 0;
  let noUrl = 0;
  let totalBytesAll = 0;

  for (let i = 0; i < targets.length; i++) {
    const item = targets[i];

    console.log(
      `\n[${i + 1}/${targets.length}] id=${item.id} - ${item.name?.slice(0, 40)}...`,
    );

    let productId = item.id ? String(item.id) : null;

    if (!productId && item.url) {
      productId = extractProductId(item.url);
    }

    if (!productId) {
      console.log(`  ✗ Не удалось определить ID (нет id и url)`);
      noUrl++;
      continue;
    }

    let sgrNumber = null;
    try {
      const { sgrNumber: sgr, bytes } = await parseSgrFromCard(productId);
      sgrNumber = sgr;
      totalBytesAll += bytes;
      const mb = (bytes / 1024 / 1024).toFixed(2);
      if (sgrNumber) {
        console.log(`  ✓ SGR: ${sgrNumber} | ${mb} МБ`);
      } else {
        console.log(`  - SGR не найден | ${mb} МБ`);
      }
    } catch (err) {
      console.error(`  ✗ Ошибка: ${err.message}`);
    }

    if (sgrNumber) {
      item.sgr_number = sgrNumber;
      updated++;
      console.log(`  → Обновлён sgr_number`);
    } else {
      stillNull++;
      console.log(`  → sgr_number оставлен null`);
    }

    if (i < targets.length - 1) {
      const delay = 3000 + Math.random() * 3000;
      await new Promise((r) => setTimeout(r, delay));
    }

    if ((i + 1) % 10 === 0) {
      const output = { quantity: items.length, data: items };
      fs.writeFileSync(inputPath, JSON.stringify(output, null, 2), "utf8");
      console.log(`  Промежуточное сохранение (${i + 1}/${targets.length})`);
    }
  }

  const output = {
    quantity: items.length,
    data: items,
  };
  fs.writeFileSync(inputPath, JSON.stringify(output, null, 2), "utf8");

  console.log(`\n--- Итог ---`);
  console.log(`Всего товаров с null: ${targets.length}`);
  console.log(`Обновлено (нашли SGR): ${updated}`);
  console.log(`Осталось null: ${stillNull}`);
  console.log(`Пропущено (нет ID/url): ${noUrl}`);
  console.log(`Общий трафик: ${(totalBytesAll / 1024 / 1024).toFixed(2)} МБ`);
  console.log(`Сохранено в ${inputPath}`);
}

main().catch((err) => {
  console.error("Критическая ошибка:", err);
  process.exit(1);
});
