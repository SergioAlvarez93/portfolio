const API_URL = "https://anyprint.pro/wp/wp-json/wp/v2";

export async function fetchAPI(endpoint) {
  const res = await fetch(`${API_URL}${endpoint}`, {
    headers: {
      "X-API-Key": "aJ7kL2mN9pQrS4uV8wXyZ1bC3dE5fG6hI0jKlMnOpQrStUvWxYz",
    },
  });

  if (!res.ok) {
    throw new Error(`Ошибка API: ${res.status} — ${res.statusText}`);
  }

  return res.json();
}

export async function getPosts() {
  return fetchAPI("/posts?_embed");
}

export async function getPost(slug) {
  const posts = await fetchAPI(`/posts?slug=${slug}&_embed`);
  return posts[0] || null;
}

export async function getPages() {
  return fetchAPI("/pages?_embed");
}

export async function getPage(slug) {
  const pages = await fetchAPI(`/pages?slug=${slug}&_embed`);
  return pages[0] || null;
}

export async function getMenu() {
  const res = await fetch(
    `${API_URL.replace("/wp/v2", "")}/acf/v3/options/options`,
  );
  const data = await res.json();
  return data.acf?.menu_items || [];
}

export async function getMedia(id) {
  return fetchAPI(`/media/${id}`);
}

async function resolveMedia(field) {
  if (field === null || field === undefined) return field;

  if (typeof field === "number" && field > 0) {
    try {
      const media = await fetchAPI(`/media/${field}`);
      return {
        url: media?.source_url || null,
        alt: media?.alt_text || "",
        width: media?.media_details?.width || 300,
        height: media?.media_details?.height || 300,
      };
    } catch (e) {
      console.warn(`Ошибка загрузки медиа ${field}`);
      return { url: null, alt: "" };
    }
  }

  if (Array.isArray(field)) {
    return Promise.all(field.map((item) => resolveMedia(item)));
  }

  if (typeof field === "object") {
    const result = { ...field };

    for (const key in result) {
      result[key] = await resolveMedia(result[key]);
    }
    return result;
  }

  return field;
}

// ─── Основная функция ─────────────────────────────
export async function getPageData(slug = "sample-page") {
  const pages = await fetchAPI(`/pages?slug=${slug}`);
  const page = pages[0];

  if (!page) return null;

  let acf = page.acf || {};

  acf = await resolveMedia(acf);

  const preLink = page.link || "";
  const homepage = preLink ? preLink.replace(/\/wp\/.*$/, "/") : "/";

  return { ...acf, homepage };
}
