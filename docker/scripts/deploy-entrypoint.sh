#!/usr/bin/env bash

# Exit on fail
set -e

# Migrate DB
php artisan migrate --force

# Start fpm
php-fpm

# Finally call command issued to the docker service
exec "$@"
