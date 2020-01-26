FROM composer:1.9.1 as composer

################################################################################

FROM php:7.2-fpm as prod

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y \
        zlib1g-dev \
        unzip

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

RUN pecl install apcu-5.1.8 \
    && docker-php-ext-enable apcu

WORKDIR /app

COPY composer.* /app/

RUN composer install --prefer-dist --no-scripts --no-dev \
    && composer clear-cache

COPY ./ /app/

RUN php -d display_errors=on bin/console cache:warmup --env=prod

CMD ["php-fpm"]
