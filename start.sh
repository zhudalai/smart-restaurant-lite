#!/bin/sh
set -e

# Run migrations (with seed on first deploy)
cd /var/www/html
php artisan migrate --force 2>/dev/null || true

# Start php-fpm and nginx
php-fpm -D
nginx -g 'daemon off;'
