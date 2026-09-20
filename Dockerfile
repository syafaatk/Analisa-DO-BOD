# Staging image for Laravel + React/Vite
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY resources ./resources
COPY vite.config.js ./
COPY public ./public
RUN npm run build

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader

FROM php:8.4-cli-alpine
WORKDIR /var/www/html
RUN apk add --no-cache libzip-dev oniguruma-dev icu-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath intl zip xml
COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=frontend /app/public/build ./public/build
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache
EXPOSE 8080
CMD ["sh", "-c", "export DB_CONNECTION=${DB_CONNECTION:-mysql}; export DB_HOST=${DB_HOST:-$MYSQLHOST}; export DB_PORT=${DB_PORT:-$MYSQLPORT}; export DB_DATABASE=${DB_DATABASE:-$MYSQLDATABASE}; export DB_USERNAME=${DB_USERNAME:-$MYSQLUSER}; export DB_PASSWORD=${DB_PASSWORD:-$MYSQLPASSWORD}; export CACHE_STORE=${CACHE_STORE:-file}; export QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}; php artisan optimize:clear && php artisan serve --host=0.0.0.0 --port=$PORT"]
