FROM php:8.4-fpm

ENV UPLOAD_MAX_FILESIZE=12M \
    POST_MAX_SIZE=15M

RUN echo "upload_max_filesize = ${UPLOAD_MAX_FILESIZE}" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = ${POST_MAX_SIZE}" >> /usr/local/etc/php/conf.d/uploads.ini

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype-dev \
    zip \
    libzip-dev \
    unzip \
    git \
    libicu-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip intl bcmath opcache exif
# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
