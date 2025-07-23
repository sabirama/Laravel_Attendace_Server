#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --optimize-autoloader && php artisan key:generate && php artisan config:cache
echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force
