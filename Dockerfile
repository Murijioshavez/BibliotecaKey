# Usando Node 20 para npm
FROM node:20 AS node_build
WORKDIR /var/www/html
COPY package*.json ./
RUN npm install
COPY . .

# PHP-FPM
FROM php:8.2.5-fpm

# Instalar dependencias PHP
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar Node y node_modules del build anterior
COPY --from=node_build /var/www/html /var/www/html

WORKDIR /var/www/html

# Generar key de Laravel
RUN composer install
RUN php artisan key:generate

EXPOSE 8000

CMD sh -c "php artisan migrate --force && php artisan queue:work & php artisan serve --host=0.0.0.0 --port=8000"
