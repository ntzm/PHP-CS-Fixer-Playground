FROM composer:1.6 as build

WORKDIR /build

COPY composer.* ./

RUN composer install --prefer-dist

FROM php:7.2-apache

WORKDIR /var/www/

EXPOSE 8000

COPY --from=build /build/vendor .

COPY . .
