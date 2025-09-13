FROM ubuntu:22.04

# Evitar preguntas interactivas durante la instalación
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    wget \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libpq-dev \
    libsqlite3-dev \
    supervisor \
    cron \
    nano \
    && rm -rf /var/lib/apt/lists/*

# Instalar PHP 8.2 y extensiones necesarias
RUN add-apt-repository ppa:ondrej/php -y && \
    apt-get update && \
    apt-get install -y \
    php8.2 \
    php8.2-cli \
    php8.2-fpm \
    php8.2-common \
    php8.2-mysql \
    php8.2-sqlite3 \
    php8.2-zip \
    php8.2-gd \
    php8.2-mbstring \
    php8.2-curl \
    php8.2-xml \
    php8.2-bcmath \
    php8.2-pdo \
    php8.2-pdo-sqlite \
    php8.2-pdo-mysql \
    php8.2-tokenizer \
    php8.2-redis \
    php8.2-simplexml \
    php8.2-dom \
    php8.2-intl

# Instalar Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar PHP-FPM
RUN mkdir -p /run/php && \
    sed -i 's/;daemonize = yes/daemonize = no/' /etc/php/8.2/fpm/php-fpm.conf && \
    sed -i 's/listen = \/run\/php\/php8.2-fpm.sock/listen = 9000/' /etc/php/8.2/fpm/pool.d/www.conf && \
    sed -i 's/;clear_env = no/clear_env = no/' /etc/php/8.2/fpm/pool.d/www.conf

# Crear usuario y grupo para la aplicación
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

# Crear directorio de la aplicación
RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# Copiar archivos de la aplicación
COPY . .

# Configurar permisos
RUN chown -R www:www /var/www/html && \
    chmod -R 755 /var/www/html/storage && \
    chmod -R 755 /var/www/html/bootstrap/cache

# Copiar configuraciones
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/crontab /etc/cron.d/laravel-cron

# Dar permisos al archivo cron
RUN chmod 0644 /etc/cron.d/laravel-cron && \
    crontab /etc/cron.d/laravel-cron

# Exponer puertos
EXPOSE 9000
EXPOSE 5173

# Cambiar al usuario www
USER www

# Instalar dependencias de Composer y Node
RUN composer install --no-interaction --optimize-autoloader --no-dev && \
    npm install && \
    npm run build

# Volver a root para supervisord
USER root

# Punto de entrada
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
