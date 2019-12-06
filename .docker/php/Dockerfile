FROM php:7.2-fpm

RUN apt-get update
RUN apt-get install -y zlib1g-dev
RUN apt-get install -y unzip

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install zip

RUN pecl install apcu-5.1.8
RUN docker-php-ext-enable apcu

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && mv composer /usr/local/bin/composer

WORKDIR /usr/src/app

# hard copy files for production environment
# these will be overidden by the 'volume' directive in the docker-compose.yml
COPY . /usr/src/app

RUN PATH=$PATH:/usr/src/apps/vendor/bin:bin