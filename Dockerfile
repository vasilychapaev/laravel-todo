FROM php:8.4-cli

# Установим нужные расширения
RUN apt-get update && apt-get install -y \
    sqlite3 libsqlite3-dev unzip \
    && docker-php-ext-install pdo pdo_sqlite

# Установим Composer
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www