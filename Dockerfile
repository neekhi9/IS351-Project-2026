FROM php:8.2-fpm

# ----------------------------
# System dependencies
# ----------------------------
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    pkg-config \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# ----------------------------
# PHP extensions
# ----------------------------
# Configure GD with freetype and jpeg support
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Install extensions in groups
RUN docker-php-ext-install pdo pdo_pgsql mbstring bcmath zip
RUN docker-php-ext-install intl exif pcntl
RUN docker-php-ext-install gd xml

# ----------------------------
# Composer
# ----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Composer memory fix
ENV COMPOSER_MEMORY_LIMIT=-1

# ----------------------------
# App directory
# ----------------------------
WORKDIR /var/www

# Copy composer files first (for caching)
COPY composer.json composer.lock ./

# Install PHP dependencies (verbose logs)
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    -vvv --prefer-ipv4

# Copy full project
COPY . .

# ----------------------------
# Node dependencies + build
# ----------------------------
RUN npm install
RUN npm run build

# ----------------------------
# Permissions (important for Laravel)
# ----------------------------
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache || true

CMD php artisan serve --host=0.0.0.0 --port=$PORT
