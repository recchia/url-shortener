FROM php:8.0-fpm-alpine
LABEL maintainer="Piero Recchia <www.pierorecchia.com>"

RUN apk update && apk add --no-cache zlib-dev icu-dev autoconf build-base libpq libzip-dev imagemagick git mysql-client postgresql-dev \
    && apk add --update npm yarn \
	&& docker-php-ext-install opcache intl zip mysqli pdo_mysql pgsql pdo_pgsql \
	&& php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
	&& chmod +sx /usr/local/bin/composer

EXPOSE 9000
