FROM php:8.0-alpine3.16

WORKDIR /home/php/src

COPY nginx.conf /etc/conf.d/default.conf

COPY composer.json .
COPY composer.lock .

RUN apk add --update linux-headers \
    && apk add --no-cache $PHPIZE_DEPS \
    && apk add --no-cache php-openssl \
    && apk add --no-cache libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk add --no-cache certbot \
    && sudo certbot --nginx \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev

EXPOSE 80
EXPOSE 443
EXPOSE 9000

CMD ["nginx", "-g", "daemon off;"]