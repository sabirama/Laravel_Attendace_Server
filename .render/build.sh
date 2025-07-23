#!/usr/bin/env bash
# .render/build.sh

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

# Optimize Laravel
php artisan optimize:clear
php artisan optimize
