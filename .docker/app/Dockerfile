ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm-alpine

ARG UID=1000
ARG GID=1000

RUN apk -U upgrade && apk add --no-cache \
    shadow \
    postgresql-dev \
    openssh-client \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo_pgsql opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && usermod -u $UID www-data -s /bin/bash && groupmod -g $GID www-data \
    && chmod 0777 /var/log \
    && apk del \
    $PHPIZE_DEPS \
    && rm -rf /tmp/* /var/tmp/* /usr/share/doc/* /var/cache/apk/* /usr/share/php*

COPY conf/php.ini /usr/local/etc/php/php.ini
COPY conf/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf

USER www-data

WORKDIR /var/www/multiple-choices-test
