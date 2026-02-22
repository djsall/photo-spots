# --- Stage 1: Build Assets ---
FROM node:20-alpine AS asset-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.4-fpm-alpine

# Install system dependencies & PHP extensions
RUN apk add --no-cache libpng-dev libjpeg-turbo-dev freetype-dev zip libzip-dev unzip git icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip intl bcmath opcache

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Copy the compiled assets from the first stage
COPY --from=asset-builder /app/public/build /var/www/html/public/build

WORKDIR /var/www/html

COPY . .
# Update permissions path too
RUN chown -R www-data:www-data /var/www/html

# Deployment script as entrypoint
COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
