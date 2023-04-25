FROM php:8.1-fpm-alpine

WORKDIR /app

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
    install-php-extensions pdo_mysql;

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer:2.4 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --prefer-dist --no-dev --no-scripts --no-progress --no-interaction --ignore-platform-req=ext-http

RUN composer dump-autoload --optimize

COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN set -eux; \
    install-php-extensions xdebug