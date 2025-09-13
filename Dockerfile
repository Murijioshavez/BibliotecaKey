# Stage 1: Node + PHP deps
FROM node:20 AS node_build
WORKDIR /var/www/html

# Instalar PHP CLI + dependencias necesarias + Composer
RUN apt-get update && apt-get install -y \
    php-cli unzip curl git libsqlite3-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd dom simplexml \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar PHP + Node deps
COPY composer.json composer.lock ./
COPY package*.json ./

# Instalar PHP deps
RUN composer install --no-dev --optimize-autoloader

# Instalar Node deps
RUN npm install

# Copiar todo el proyecto
COPY . .

# Compilar frontend (Vite)
RUN npm run build

# Stage 2: PHP-FPM
FROM php:8.2.5-fpm

# Instalar dependencias PHP y sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd dom simplexml

# Instalar Composer
COPY --from=node_build /usr/local/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Copiar proyecto completo + frontend compilado + vendor
COPY --from=node_build /var/www/html /var/www/html

# Generar key si no existe
RUN if [ ! -f .env ]; then cp .env.example .env; fi && php artisan key:generate

# Exponer puerto
EXPOSE 8000

# Comando de inicio
CMD sh -c "php artisan migrate --force && php artisan queue:work & php artisan serve --host=0.0.0.0 --port=8000"
