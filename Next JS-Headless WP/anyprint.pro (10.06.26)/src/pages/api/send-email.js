import nodemailer from "nodemailer";

export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ message: "Метод не разрешён" });
  }

  const { name, email, tel, text } = req.body;

  if (!name || !email || !tel) {
    return res.status(400).json({ message: "Заполните обязательные поля" });
  }

  const transporter = nodemailer.createTransport({
    host: process.env.SMTP_HOST,
    port: Number(process.env.SMTP_PORT),
    secure: true,
    auth: {
      user: process.env.SMTP_USER,
      pass: process.env.SMTP_PASS,
    },
  });

  try {
    await transporter.sendMail({
      from: process.env.SMTP_FROM,
      to: process.env.SMTP_FROM,
      subject: `Заявка с сайта от ${name}`,
      html: `
        <h2>Новая заявка</h2>
        <p><b>Имя:</b> ${name}</p>
        <p><b>Email:</b> ${email}</p>
        <p><b>Телефон:</b> ${tel}</p>
        <p><b>Сообщение:</b> ${text || "—"}</p>
      `,
    });

    return res.status(200).json({ message: "Письмо отправлено" });
  } catch (error) {
    console.error("Ошибка отправки:", error);
    return res.status(500).json({ message: "Ошибка отправки письма" });
  }
}
