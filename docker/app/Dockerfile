FROM php:8.1-fpm

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        vim \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && : 'Install Node.js' \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \

ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV LANG C.UTF-8

WORKDIR /var/www/html
