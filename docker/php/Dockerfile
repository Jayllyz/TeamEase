FROM php:8.0.0-alpine

WORKDIR /home/php/src

COPY . .

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk add --no-cache php-openssl \
    && apk add --no-cache libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install

