FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libssl-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Redis
RUN pecl install redis && docker-php-ext-enable redis

# Instalacja Node.js (LTS) oraz npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
