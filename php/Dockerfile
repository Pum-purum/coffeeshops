FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    librabbitmq-dev \
    libssl-dev \
    && docker-php-ext-install sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp

WORKDIR /app
