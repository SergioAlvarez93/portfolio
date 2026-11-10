# Используем Node.js 20 на Alpine (легковесный образ)
FROM node:20-alpine

# Устанавливаем рабочую директорию
WORKDIR /app

# Копируем package.json и package-lock.json
COPY package*.json ./

# Устанавливаем зависимости
RUN npm ci --production

# Копируем остальной проект
COPY . .

# Собираем Next.js
RUN npm run build

# Открываем порт 3000
EXPOSE 3000

# Запускаем Next.js
CMD ["npm", "start"]