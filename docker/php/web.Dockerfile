FROM php:8.3-fpm-alpine as php_base

RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libwebp-dev \
    libxpm-dev \
    git

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html


COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

FROM node:20-alpine as node_base
WORKDIR /app
COPY . .
RUN npm ci
# Compilar assets (Vite build)
RUN npm run build


FROM php_base
COPY --from=node_base /app/public/build /var/www/html/public/build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache