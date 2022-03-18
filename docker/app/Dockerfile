FROM composer:2.2 as composer

################################################################################

FROM php:8.1-fpm as php

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y \
        zlib1g-dev \
        unzip \
        libzip-dev \
        git

RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

RUN useradd docker -d /home/docker -u 1000 && \
    mkdir -p /home/docker/composer /app && \
    chown -R docker:docker /home/docker /app

WORKDIR /app

RUN chown docker /

USER docker

COPY --chown=docker:docker composer.* /app/

RUN composer install $([ "$ENVIRONMENT" = 'prod' ] && echo '--no-dev --optimize-autoloader')

ENV PATH="${PATH}:vendor/bin:bin"

COPY --chown=docker:docker ./ /app/

RUN php -d display_errors=on bin/console cache:warmup --env=prod

CMD ["php-fpm"]
