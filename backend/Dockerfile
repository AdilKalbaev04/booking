# Используем базовый образ PHP
FROM php:8.2-fpm

# Устанавливаем зависимости для Laravel и PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev && \
    docker-php-ext-install pdo_pgsql

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www/html

# Копируем все файлы проекта
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-dev --optimize-autoloader

# Убедимся, что права на файлы правильные
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Экспонируем порт
EXPOSE 8000

# Запуск Laravel приложения
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
