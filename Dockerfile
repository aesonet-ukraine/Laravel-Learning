FROM  php:8.4-apache

RUN apt-get update && apt-get update -y &&apt-get install -y git

RUN apt-get install -y  \
    libzip-dev  \
    zip \
    && docker-php-ext-install zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer




