FROM php:7-fpm-alpine

RUN apk update &&\
    apk add --update --virtual build_deps bash gcc g++ autoconf make openssl-dev pcre-dev &&\
    docker-php-source extract &&\
    /bin/bash -lc "pecl install mongodb" && \
    docker-php-ext-enable mongodb && \
    docker-php-source delete && \
    apk del build_deps && \
    rm -rf /var/cache/apk/* && \
    rm -rf /tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer require mongodb/mongodb