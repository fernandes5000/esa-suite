FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

RUN apk add --no-cache nodejs npm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/web

RUN chown -R www-data:www-data /var/www/web

CMD ["php-fpm"]
