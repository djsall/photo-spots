# --- Stage 1: Build Assets ---
FROM node:20-alpine AS asset-builder
WORKDIR /build-stage

# Only copy what's needed for npm first (better caching)
COPY package*.json ./
RUN npm install

# Copy the rest and build
COPY . .
RUN npm run build

# --- Stage 2: PHP Runtime ---
FROM php:8.4-fpm-alpine

# Install dependencies
RUN apk add --no-cache libpng-dev libjpeg-turbo-dev freetype-dev zip libzip-dev unzip git icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip intl bcmath opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Copy assets from the builder stage using the correct path
COPY --from=asset-builder /build-stage/public/build /var/www/html/public/build

RUN chown -R www-data:www-data /var/www/html

COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
