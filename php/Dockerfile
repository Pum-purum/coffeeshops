FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    librabbitmq-dev \
    libssl-dev \
    zip \
    unzip \
    && docker-php-ext-install sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
