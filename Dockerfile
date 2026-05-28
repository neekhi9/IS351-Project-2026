FROM php:8.2-fpm

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring bcmath zip \
    && rm -rf /var/lib/apt/lists/*

# Node 18 for Vite build
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy full source first (important for Laravel composer scripts/autoload paths)
COPY . .

# Install PHP deps (no-dev for prod)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Frontend build
RUN npm ci && npm run build

# Permissions for Laravel runtime
RUN chown -R www-data:www-data storage bootstrap/cache

# FPM process for php:8.2-fpm image
CMD ["php-fpm"]
