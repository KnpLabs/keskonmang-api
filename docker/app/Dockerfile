FROM composer:1.9.1 as composer

################################################################################

FROM php:7.2-fpm as php

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y \
        zlib1g-dev \
        unzip

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

RUN pecl install apcu-5.1.8 \
    && docker-php-ext-enable apcu

RUN useradd docker -d /home/docker -u 1000 && \
    mkdir -p /home/docker/opcache /home/docker/composer /app && \
    chown -R docker:docker /home/docker /app

ENV COMPOSER_HOME /home/docker/composer

USER docker
WORKDIR /app

COPY composer.* /app/

RUN composer install --prefer-dist --no-scripts --no-dev && \
    composer clear-cache

COPY --chown=docker:docker ./ /app/

RUN php -d display_errors=on bin/console cache:warmup --env=prod

CMD ["php-fpm"]
