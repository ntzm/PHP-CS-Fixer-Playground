FROM php:7.2-apache

WORKDIR /var/www/

EXPOSE 8000

RUN apt-get update \
 && apt-get install -y git unzip \
 && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY composer.* ./

RUN composer install --prefer-dist --no-dev

RUN a2enmod rewrite \
 && chown www-data:www-data . \
 && docker-php-ext-install bcmath

COPY . .
