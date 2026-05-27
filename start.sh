#!/bin/sh

cd /var/www/html

# Auto-migrate on startup (no seed — seed data via seeder command)
php artisan migrate --force --seed 2>/dev/null || echo "Migrate/seed skipped"

# Start php-fpm and nginx in foreground
php-fpm -D
exec nginx -g 'daemon off;'
