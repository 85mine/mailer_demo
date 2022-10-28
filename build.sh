#!/bin/bash

# Install dependencies
composer install -n
npm install

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Create cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload -n

# Migrate
php artisan migrate --force

# Build assets
npm run build
