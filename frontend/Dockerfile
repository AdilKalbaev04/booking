# Используем базовый образ Node.js
FROM node:20

# Установка рабочей директории
WORKDIR /app

# Копируем package.json и package-lock.json
COPY package*.json ./

# Устанавливаем зависимости
RUN npm install

# Копируем все файлы проекта
COPY . .

# Собираем приложение
RUN npm run build

# Экспонируем порт
EXPOSE 5173

# Запуск приложения
CMD ["npm", "run", "dev", "--", "--host=0.0.0.0"]

