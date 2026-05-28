# =========================
# PHP BASE (Render-friendly)
# =========================
FROM php:8.2-fpm

WORKDIR /var/www/html

# =========================
# SYSTEM DEPENDENCIES
# =========================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# =========================
# COMPOSER
# =========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# COPY PROJECT FILES
# (important for Render build cache)
# =========================
COPY . .

# =========================
# ENV SAFETY FOR RENDER
# =========================
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1

# =========================
# INSTALL PHP DEPENDENCIES
# =========================
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# =========================
# INSTALL FRONTEND + BUILD VITE
# =========================
RUN npm install && npm run build

# =========================
# FIX PERMISSIONS (Render needs this)
# =========================
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# =========================
# RENDER PORT (important!)
# =========================
ENV PORT=10000
EXPOSE 10000

# =========================
# START SERVER (Render uses this)
# =========================
CMD php -S 0.0.0.0:$PORT -t public
