FROM ubuntu:24.04

ENV PHP_VERSION=8.4
ENV NODE_VERSION=22
ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    software-properties-common \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libpq-dev \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

RUN add-apt-repository ppa:ondrej/php -y && \
    apt-get update && \
    apt-get install -y \
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
    php${PHP_VERSION}-redis \
    php${PHP_VERSION}-intl \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - && \
 apt-get install -y nodejs

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/.npm
RUN chown -R www-data:www-data /var/www/.npm

RUN mkdir -p /var/www/html
WORKDIR /var/www/html

RUN chmod -R 777 /var/log

COPY --chown=www-data:www-data . .

RUN chown -R www-data:www-data /var/www/html && \
chmod -R 755 /var/www/html/storage && \
chmod -R 755 /var/www/html/bootstrap/cache

USER www-data

RUN composer install --no-interaction --optimize-autoloader --no-dev && \
    npm install && npm run build

ENV VIEW_COMPILED_PATH=/var/www/html/bootstrap/cache/views
RUN php artisan optimize --except config
