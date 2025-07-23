#!/usr/bin/env bash

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Generate application key
php artisan key:generate --force

# Optimize Laravel
php artisan optimize:clear
php artisan optimize
