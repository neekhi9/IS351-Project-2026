FROM php:8.2-fpm

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    npm \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        bcmath \
        zip \
        intl \
    && rm -rf /var/lib/apt/lists/*

# Install Node 18
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first for better cache
COPY composer.json composer.lock ./

# Install PHP deps first
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader \
    --no-scripts

# Copy application source
COPY . .

# Laravel caches
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Frontend
RUN npm install && npm run build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

CMD ["php-fpm"]
