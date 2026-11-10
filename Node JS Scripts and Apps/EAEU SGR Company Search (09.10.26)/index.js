const axios = require("axios");
const XLSX = require("xlsx");

const API_URL = "https://nsi.eaeunion.org/api/v1/dictionaries/1995/elements";
const DICTIONARY_CODE = "1995";
const PAGE_LIMIT = 1500;
const EXCLUDE_STATUS_SUBSTRINGS = ["аннулирован", "отозван"];

function normalizeCompanyName(name) {
  if (!name) return "";
  let s = String(name).toLowerCase();

  const commaIdx = s.indexOf(",");
  if (commaIdx !== -1) {
    const tail = s.slice(commaIdx + 1);

    const addressMarkers = [
      "область",
      "обл.",
      "район",
      "р-н",
      "город",
      "г.",
      "улица",
      "ул.",
      "д.",
      "дом",
      "офис",
      "пр-кт",
      "проспект",
      "шоссе",
      "пер.",
      "наб.",
      "край",
      "респ.",
      "корп",
      "строение",
      "литер",
      "адрес",
      "места осуществления",
      "почтовый",
    ];

    const hasAddressMarker =
      addressMarkers.some((m) => tail.includes(m)) || /\d{3,}/.test(tail);

    if (hasAddressMarker) {
      s = s.slice(0, commaIdx);
    }
  }

  s = s.replace(/\([^)]*\)/g, " ");

  s = s.replace(/[«»""''`]/g, "");

  s = s.replace(/[.,;:!?\-–—/\\]/g, " ");

  s = s.replace(/\bобщество с ограниченной ответственностью\b/g, "ооо");
  s = s.replace(/\bакционерное общество\b/g, "ао");
  s = s.replace(/\bпубличное акционерное общество\b/g, "пао");
  s = s.replace(/\bзакрытое акционерное общество\b/g, "зао");
  s = s.replace(/\bоткрытое акционерное общество\b/g, "оао");

  s = s.replace(/^(ооо|оао|зао|пао|ао|ип)\s+/i, "");

  s = s.replace(/\s+/g, " ").trim();

  return s;
}

function extractKeyword(company) {
  const normalized = normalizeCompanyName(company);
  const words = normalized.split(" ").filter((w) => w.length >= 3);
  if (words.length === 0) return normalized;
  return words.reduce((a, b) => (b.length > a.length ? b : a), "");
}

async function fetchSGRByCondition(conditionType, value) {
  const allElements = [];
  let offset = 0;
  let page = 0;
  const maxPages = 1000;

  while (page < maxPages) {
    const params = {
      dictionaryCode: DICTIONARY_CODE,
      "conditions[0].conditionType": conditionType,
      "conditions[0].code": "FIRMMADE_NAME",
      "conditions[0].value": value,
      offset: offset,
      limit: PAGE_LIMIT,
    };

    const response = await axios.get(API_URL, { params, timeout: 60000 });
    const payload = response.data;
    const elements = payload.elements || [];
    const pagination = payload.pagination || {};
    const serverLimit = pagination.limit || PAGE_LIMIT;
    const serverOffset = pagination.offset || offset;

    if (elements.length === 0) break;
    allElements.push(...elements);
    if (elements.length < serverLimit) break;

    const newOffset = serverOffset + serverLimit;
    if (newOffset <= offset) break;

    offset = newOffset;
    page++;
    await new Promise((r) => setTimeout(r, 300));
  }

  return allElements;
}

async function fetchSGRForCompany(company) {
  const seen = new Map();

  try {
    const byEq = await fetchSGRByCondition("eq", company);
    for (const el of byEq) if (el.id) seen.set(el.id, el);
    console.log(`  eq "${company}": ${byEq.length}`);
  } catch (err) {
    console.error(`  eq ошибка: ${err.message}`);
  }

  const keyword = extractKeyword(company);
  if (normalizeCompanyName(keyword) !== normalizeCompanyName(company)) {
    try {
      const byLike = await fetchSGRByCondition("like", keyword);
      for (const el of byLike)
        if (el.id && !seen.has(el.id)) seen.set(el.id, el);
      console.log(`  like "${keyword}": ${byLike.length}`);
    } catch (err) {
      console.error(`  like ошибка: ${err.message}`);
    }
  }

  return Array.from(seen.values());
}

function statusIsExcluded(statusName) {
  if (!statusName) return false;
  const low = statusName.toLowerCase();
  return EXCLUDE_STATUS_SUBSTRINGS.some((sub) => low.includes(sub));
}

async function extractSGRForCompanies(excelPath, companyColumn) {
  const workbook = XLSX.readFile(excelPath);
  const sheet = workbook.Sheets[workbook.SheetNames[0]];
  const rows = XLSX.utils.sheet_to_json(sheet, { header: 1 });

  const headerRow = rows[0];
  const colIndex = headerRow.indexOf(companyColumn);
  if (colIndex === -1) {
    throw new Error(
      `В файле нет столбца '${companyColumn}'. Доступные: ${headerRow.join(", ")}`,
    );
  }

  const companies = new Map();
  for (let i = 1; i < rows.length; i++) {
    const val = rows[i][colIndex];
    if (val != null && String(val).trim() !== "") {
      const original = String(val).trim();
      companies.set(original, normalizeCompanyName(original));
    }
  }

  console.log(`Компаний в файле: ${companies.size}`);

  const result = [];
  const seenIds = new Set();
  let idx = 0;

  for (const [company, targetNorm] of companies) {
    idx++;
    console.log(`\n[${idx}/${companies.size}] "${company}"`);

    let elements;
    try {
      elements = await fetchSGRForCompany(company);
    } catch (err) {
      console.error(`  Ошибка: ${err.message}`);
      continue;
    }

    console.log(`  Всего после объединения: ${elements.length}`);

    let kept = 0;
    for (const el of elements) {
      const data = el.data || {};
      const firmmade = data.FIRMMADE_NAME || "";

      if (normalizeCompanyName(firmmade) !== targetNorm) continue;

      const status = data.STATUS ? data.STATUS.name : null;
      if (statusIsExcluded(status)) continue;

      if (el.id && seenIds.has(el.id)) continue;
      if (el.id) seenIds.add(el.id);

      result.push({
        id: el.id,
        NUMB_DOC: data.NUMB_DOC,
        SERIALNUMB: data.SERIALNUMB,
        DATE_DOC: data.DATE_DOC,
        STATUS: status,
        FIRMMADE_NAME: firmmade,
        FIRMGET_NAME: data.FIRMGET_NAME,
        NAME_PROD: data.NAME_PROD,
        DOC_NORM: data.DOC_NORM,
        DOC_USEAREA: data.DOC_USEAREA,
        DOC_PROTOCOL: data.DOC_PROTOCOL,
        dateTimeFrom: el.dateTimeFrom,
        dateTimeTo: el.dateTimeTo,
        dateTimeUpdated: el.dateTimeUpdated,
      });
      kept++;
    }

    console.log(`  Оставлено: ${kept}`);
    await new Promise((r) => setTimeout(r, 300));
  }

  return result;
}

function saveToExcel(data, outputPath) {
  if (data.length === 0) {
    console.log("Нет данных для сохранения.");
    return;
  }
  const worksheet = XLSX.utils.json_to_sheet(data);
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, "SGR");
  XLSX.writeFile(workbook, outputPath);
  console.log(`Сохранено в ${outputPath}`);
}

(async () => {
  try {
    const EXCEL_PATH = "companies.xlsx";
    const COMPANY_COLUMN = "Компания";
    const OUTPUT_PATH = "sgr_filtered.xlsx";

    const filtered = await extractSGRForCompanies(EXCEL_PATH, COMPANY_COLUMN);
    console.log(`\nИтого найдено записей: ${filtered.length}`);
    saveToExcel(filtered, OUTPUT_PATH);
  } catch (err) {
    console.error("Ошибка:", err.message);
    process.exit(1);
  }
})();
