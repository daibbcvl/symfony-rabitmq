FROM php:7.1.3-alpine

RUN apk add --update zlib-dev $PHPIZE_DEPS
RUN docker-php-ext-install zip pdo_mysql

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www
WORKDIR /var/www

EXPOSE 8000
