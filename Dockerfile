FROM serversideup/php:8.4-fpm-nginx AS base

ENV PHP_OPCACHE_ENABLE=1 \
    PHP_UPLOAD_MAX_FILE_SIZE=12M \
    PHP_POST_MAX_SIZE=15M

# Extensions not included in the base image
USER root
RUN install-php-extensions bcmath exif gd intl
USER www-data

# Install dependencies first so this layer is cached until composer.lock changes
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --no-autoloader --prefer-dist

# Frontend assets (needs vendor/ for the Flux CSS)
FROM node:24-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
COPY --from=base /var/www/html/vendor ./vendor
RUN npm run build

FROM base
COPY --chown=www-data:www-data . .
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev
