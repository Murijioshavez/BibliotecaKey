# Usamos PHP FPM
FROM php:8.2.5-fpm

# Instalar dependencias de sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Carpeta de trabajo
WORKDIR /var/www/html

# Copiar todo el proyecto
COPY . .

# Instalar dependencias PHP y Node
RUN composer install
RUN npm install

# Generar key de Laravel
RUN php artisan key:generate

# Exponer puerto de Laravel
EXPOSE 8000

# Comando para iniciar Laravel y colas juntos (solo dev)
CMD sh -c "php artisan migrate --force && php artisan queue:work & php artisan serve --host=0.0.0.0 --port=8000"
