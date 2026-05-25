# FROM php:8.2-fpm

# WORKDIR /var/www/html

# RUN apt-get update && apt-get install -y \
#     git curl unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev \
#     && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# # Composer
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# COPY . .

# RUN composer install --no-dev --optimize-autoloader

FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev

# Install PostgreSQL PHP extensions
RUN docker-php-ext-install pdo_pgsql pgsql

# Install other PHP extensions
RUN docker-php-ext-install pdo mbstring bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
