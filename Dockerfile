#syntax=docker/dockerfile:1
ARG PHP_VERSION=8.4
ARG PHP_IMAGE=php:${PHP_VERSION}-fpm-alpine

# INSTALL PHP EXTENSIONS
FROM ${PHP_IMAGE} AS builder
WORKDIR /var/www/jobs-app
# Install and enable PHP extensions with https://github.com/mlocati/docker-php-extension-installer
RUN curl -sSLf \
    -o /usr/local/bin/install-php-extensions \
    https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions \
    pdo_mysql \
    zip \
    exif  \
    redis \
    xdebug \
    @composer

# some useful extensions for the future, I dont want to google anymore
# used for image manipulation, might be useful generating images for several resolutions
# gd \
# same as gd but more powerful
# imagick \
# used for financial calculations, could be useful in the future
# bcmath \ 

FROM builder AS app
WORKDIR /var/www/jobs-app

# ARG NODE_VERSION=20.11.0-r0
# ARG NPM_VERSION=10.4.0-r0
# openjdk11 nodejs=${NODE_VERSION} npm=${NPM_VERSION} 
# RUN npm install -g @openapitools/openapi-generator-cli

RUN apk update && apk --no-cache add bash shadow git 

# install openapi-generator-cli via npm

ARG user=PHP_CONTAINER_USER
ARG uid=PHP_CONTAINER_UID
ARG group=PHP_CONTAINER_GROUP

RUN groupadd -g ${uid} ${group} && \
    useradd -u ${uid} -ms /bin/bash -g ${group} ${user}

COPY --chown=${user}:${group} . /var/www/jobs-app

# Set an environment variable with default value "development"
ENV PHP_ENVIRONMENT=development
# Copy the appropriate php.ini file based on the environment
RUN if [ "$PHP_ENVIRONMENT" = "production" ]; then \
    mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini; \
    else \
    mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini; \
    fi

RUN  git config --global --add safe.directory /var/www/jobs-app
RUN chown -R www-data:www-data /var/www/jobs-app/storage

USER ${user}

VOLUME ["/var/www/jobs-app/"]
EXPOSE 9000
CMD ["php-fpm"]
