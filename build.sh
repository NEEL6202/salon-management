#!/usr/bin/env bash
# exit on error
set -o errexit

echo "Starting build process..."

# Create storage directories if they don't exist
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p database

# Create SQLite database file for production
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    echo "Created SQLite database file"
fi

# Set permissions
chmod -R 775 storage bootstrap/cache database

echo "Installing Composer dependencies..."
# Install Composer dependencies
composer install --no-dev --optimize-autoloader --no-interaction

echo "Clearing caches..."
# Clear and cache config
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Running database migrations..."
# Run migrations with SQLite
php artisan migrate --force --seed

echo "Optimizing application..."
# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Build completed successfully!"
