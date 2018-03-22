FROM composer:1.6 as build

WORKDIR /build

COPY composer.* ./

RUN composer install --prefer-dist

FROM php:7.2

WORKDIR /app

EXPOSE 8000

COPY --from=build /build/vendor .

COPY . .

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "index.php"]
