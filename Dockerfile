FROM php:8.4-apache

# Оновлення та базові утиліти
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    zip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip

# Встановлення Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копіювання коду Laravel (як приклад)
# COPY . /var/www/html

# Налаштування прав, якщо потрібно
# RUN chown -R www-data:www-data /var/www/html

# Відкритий порт Apache
ARG WWWGROUP=1000
ARG WWWUSER=1000

EXPOSE 80
