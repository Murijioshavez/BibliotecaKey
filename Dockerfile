# Stage 1: Build frontend
FROM node:20 AS node_build
WORKDIR /var/www/html

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build  # <-- Esto genera public/build

# Stage 2: PHP-FPM
FROM php:8.2.5-fpm

# Instalar dependencias PHP y sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar el proyecto completo + frontend compilado
COPY --from=node_build /var/www/html /var/www/html

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Generar key si no existe
RUN if [ ! -f .env ]; then cp .env.example .env; fi && php artisan key:generate

# Exponer puerto
EXPOSE 8000

# Comando de inicio (Laravel + colas)
CMD sh -c "php artisan migrate --force && php artisan queue:work & php artisan serve --host=0.0.0.0 --port=8000"
