export default async function handler(req, res) {
  if (req.query.secret !== "0589ea0d-1a31-4827-ba00-e5c9ce87c4ff") {
    return res.status(401).json({ message: "Неверный ключ" });
  }

  const path = req.query.path;

  if (!path) {
    return res.status(400).json({ message: "Укажите путь (path)" });
  }

  try {
    await res.revalidate(path);
    return res.json({ revalidated: true, path: path });
  } catch (err) {
    return res.status(500).json({ message: "Ошибка ревалидации" });
  }
}
