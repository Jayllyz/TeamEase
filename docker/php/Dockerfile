FROM php:8.0.0-alpine

COPY composer.json /home/php/src/composer.json

COPY composer.lock /home/php/src/composer.lock

WORKDIR /home/php/src

RUN apk add --update linux-headers

RUN apk add --no-cache $PHPIZE_DEPS

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN apk add --no-cache php-openssl

RUN apk add --no-cache libjpeg-turbo-dev libpng-dev && \
    docker-php-ext-configure gd --with-jpeg=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install