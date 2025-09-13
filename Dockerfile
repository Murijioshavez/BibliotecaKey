FROM php:8.2.5-fpm

# Instalar dependencias PHP y sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar todo el proyecto
COPY . .

# Instalar dependencias PHP
RUN composer install

# Instalar dependencias Node
RUN npm install

# Exponer puerto
EXPOSE 8000

# Comando de inicio (Laravel + colas)
CMD sh -c "\
  if [ ! -f .env ]; then cp .env.example .env; fi && \
  php artisan key:generate && \
  php artisan migrate --force && \
  php artisan queue:work & \
  php artisan serve --host=0.0.0.0 --port=8000"
