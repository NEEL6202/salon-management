#!/usr/bin/env bash
# exit on error
set -o errexit

# Install Composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 775 storage bootstrap/cache
