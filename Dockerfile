FROM php:8.4-fpm

# Install system deps, PHP extensions, nginx, nodejs
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    nginx nodejs npm \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# PHP dependencies (production)
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Frontend build
RUN npm ci && npm run build

# nginx config
COPY nginx/default.conf /etc/nginx/sites-available/default

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Start php-fpm and nginx
COPY start.sh /start.sh
RUN chmod +x /start.sh && sed -i 's/\r$//' /start.sh
CMD ["sh", "/start.sh"]
