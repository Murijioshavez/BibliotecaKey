# Stage 1: PHP + Node - build everything
FROM php:8.2.5-fpm AS build
WORKDIR /var/www/html

# Instalar PHP deps necesarias
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd dom simplexml \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copiar solo composer.json y composer.lock
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copiar Node deps
COPY package*.json ./
RUN npm install

# Copiar el resto del proyecto
COPY . .

# Generar los assets de frontend
RUN npm run build

# Stage 2: PHP-FPM production
FROM php:8.2.5-fpm
WORKDIR /var/www/html

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd dom simplexml \
    && rm -rf /var/lib/apt/lists/*

# Copiar Composer bin
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copiar todo desde el stage build (PHP + Node)
COPY --from=build /var/www/html /var/www/html

# Generar .env si no existe
RUN cp .env.example .env && php artisan key:generate

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
