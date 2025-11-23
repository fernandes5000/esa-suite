FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    openssl \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libxslt1-dev \
    && docker-php-ext-install pdo_mysql mbstring xml zip xsl \
    && rm -rf /var/lib/apt/lists/*

# Add Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN mkdir -p storage/framework/{cache,views,sessions} bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

CMD ["php-fpm"]
