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
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    bcmath \
    zip \
    intl \
    exif \
    pcntl \
    gd

# ----------------------------
# Composer
# ----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ----------------------------
# App directory
# ----------------------------
WORKDIR /var/www

# Copy composer files first (for caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    -vvv

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
RUN chmod -R 775 storage bootstrap/cache || true
