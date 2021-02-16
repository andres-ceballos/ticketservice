FROM php:8.0-fpm-alpine

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli pdo pdo_mysql

#NORMAL VERSION
#USER www-data:www-data

#ALPINE VERSION
RUN apk add shadow && usermod -u 1000 www-data && groupmod -g 1000 www-data