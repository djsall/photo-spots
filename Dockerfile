FROM php:8.4-fpm-alpine

ENV UPLOAD_MAX_FILESIZE=12M \
    POST_MAX_SIZE=15M

RUN echo "upload_max_filesize = ${UPLOAD_MAX_FILESIZE}" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = ${POST_MAX_SIZE}" >> /usr/local/etc/php/conf.d/uploads.ini

# Install system dependencies
RUN apk add --no-cache libpng-dev libjpeg-turbo-dev freetype-dev zip libzip-dev unzip git icu-dev nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip intl bcmath opcache

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
