FROM php:8.2-fpm-alpine

ARG USER_UID
ARG USER_GID
ARG USER_NAME

RUN apk add --no-cache \
    git \
    unzip \
    icu-dev \
    libzip-dev \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup -g ${USER_GID} ${USER_NAME} && \
    adduser -D -u ${USER_UID} -G ${USER_NAME} -s /bin/sh ${USER_NAME}

USER ${USER_NAME}

WORKDIR /var/www/html

CMD ["php-fpm"]