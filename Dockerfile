# Stage 1: Node - build frontend
FROM node:20 AS node_build
WORKDIR /var/www/html

# Copiar solo package.json para cachear npm install
COPY package*.json ./
RUN npm install

# Copiar todo el proyecto y compilar assets
COPY . .
RUN npm run build

# Stage 2: PHP-FPM - production
FROM php:8.2.5-fpm

# Instalar extensiones y herramientas necesarias
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd dom simplexml \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Copiar el proyecto completo + frontend compilado
COPY --from=node_build /var/www/html /var/www/html

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Copiar .env y generar key
RUN cp .env.example .env && php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
