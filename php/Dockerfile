FROM php:fpm-alpine

# Instalar dependencias necesarias
RUN apk add --no-cache icu-dev

# Instalar las extensiones de PHP necesarias
RUN docker-php-ext-install opcache pdo_mysql intl

# Copiar la configuración de opcache
COPY php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copiar la aplicación
COPY ./contabilidad/ /var/www/html/