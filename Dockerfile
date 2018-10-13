FROM node:10 as build-assets

COPY package* ./

RUN npm ci

COPY gulpfile.js .
COPY assets assets

RUN node_modules/.bin/gulp

FROM php:7.2-apache

WORKDIR /var/www/

EXPOSE 8000

RUN apt-get update \
 && apt-get install -y \
    libpq-dev \
    unzip \
 && docker-php-ext-install \
    opcache \
    pdo_pgsql \
 && a2enmod rewrite \
 && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY composer.* ./

RUN composer install --optimize-autoloader --prefer-dist --no-dev

COPY --from=build-assets html/app.js html
COPY --from=build-assets html/style.css html

RUN chown www-data:www-data .

COPY . .
