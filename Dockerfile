FROM node:10 as build-assets

COPY package* ./
COPY gulpfile.js .

RUN npm install \
 && node_modules/.bin/gulp

FROM php:7.2-apache

WORKDIR /var/www/

EXPOSE 8000

RUN apt-get update \
 && apt-get install -y \
    git \
    zlib1g-dev \
 && docker-php-ext-install \
    bcmath \
    zip \
 && a2enmod rewrite \
 && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

COPY composer.* ./

RUN composer install --prefer-dist --no-dev

COPY --from=build-assets html/app.js html
COPY --from=build-assets html/style.css html

RUN chown www-data:www-data .

COPY . .
