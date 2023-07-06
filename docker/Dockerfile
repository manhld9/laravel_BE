# base
FROM php:8.1.5-fpm-alpine3.15 AS base

RUN apk add --update --no-cache bash git

ADD https://github.com/mlocati/docker-php-extension-installer/releases/download/1.5.33/install-php-extensions /usr/local/bin/

ENV IPE_GD_WITHOUTAVIF=1

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    @composer-2.3.5 \
    pdo_mysql \
    gd \
    xdebug-3.1.5

COPY ./docker/config/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY ./docker/crontab /etc/cron.d/crontab
RUN crontab /etc/cron.d/crontab

ENV PATH=/var/www/html/vendor/bin:$PATH

# build
FROM base

ENV XDEBUG_MODE=off

COPY --chown=www-data:www-data composer.json composer.lock ./

COPY ./docker/config/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

RUN composer install --no-autoloader -n --no-scripts --no-dev

COPY --chown=www-data:www-data . .

RUN composer dump-autoload -o -n

CMD ["bash", "./docker/scripts/deploy-entrypoint.sh"]
