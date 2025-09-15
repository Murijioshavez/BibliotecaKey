FROM ubuntu:24.04

ENV PHP_VERSION=8.4
ENV NODE_VERSION=22
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    wget \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Agregar repositorios
RUN add-apt-repository ppa:ondrej/php -y

# Instalar PHP
RUN apt-get update && apt-get install -y \
    php${PHP_VERSION} \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-common \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-sqlite3 \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-opcache \
    && rm -rf /var/lib/apt/lists/*

# Instalar Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - && \
    apt-get install -y nodejs

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar PHP-FPM
RUN sed -i 's/;daemonize = yes/daemonize = no/' /etc/php/${PHP_VERSION}/fpm/php-fpm.conf && \
    sed -i 's/listen = .*/listen = 9000/' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf && \
    sed -i 's/;clear_env = no/clear_env = no/' /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf

# Crear usuario
RUN useradd -m -u 1000 -s /bin/bash www-data && \
    mkdir -p /var/www/html && \
    chown -R www-data:www-data /var/www

WORKDIR /var/www/html

# Copiar aplicación
COPY --chown=www-data:www-data . .

# Configurar permisos DEFINITIVOS
RUN mkdir -p storage/logs storage/framework storage/framework/cache \
    storage/framework/sessions storage/framework/views \
    bootstrap/cache database

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 775 storage bootstrap/cache database && \
    chmod -R 777 storage/logs

# Crear base de datos
RUN touch database/database.sqlite && \
    chown www-data:www-data database/database.sqlite && \
    chmod 666 database/database.sqlite

# Instalar dependencias
USER www-data
RUN composer install --no-interaction --optimize-autoloader --no-dev && \
    npm install && \
    npm run build

# Optimizar
RUN php artisan optimize

# Puerto para PHP-FPM
EXPOSE 9000

# Comando para PHP-FPM
CMD ["php-fpm8.4", "-F", "-R"]
