FROM node:20-bookworm-slim AS frontend_assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm run build

FROM php:8.3-fpm

WORKDIR /var/www

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    curl \
    git \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libsqlite3-dev \
    libxml2-dev \
    libzip-dev \
    nginx \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        pcntl \
        pdo_mysql \
        pdo_sqlite \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader
COPY --from=frontend_assets /app/public/build ./public/build

COPY docker/nginx.conf /etc/nginx/templates/default.conf.template
COPY docker/start-container.sh /usr/local/bin/start-container

RUN rm -f /etc/nginx/sites-enabled/default \
    && chmod +x /usr/local/bin/start-container \
    && mkdir -p /run/php /var/lib/nginx/tmp/client_body /var/log/nginx /var/www/storage/framework/{cache,sessions,views} \
    && touch /var/www/database/database.sqlite \
    && chown -R www-data:www-data /var/www /run/php /var/lib/nginx /var/log/nginx \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

EXPOSE 8080

ENTRYPOINT ["/usr/local/bin/start-container"]
