FROM php:8.0-apache

COPY ./src /var/www/html

RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN a2enmod rewrite

RUN pecl install xdebug && docker-php-ext-enable xdebug