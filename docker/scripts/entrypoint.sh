#!/usr/bin/env bash

# Exit on fail
set -e

# Install packagist
composer install

# Wait for mysql to be ready
dir=$(dirname "$0")

$dir/wait-for-it.sh mysql:3306 -t 300

# Migrate DB
php artisan migrate

# Start cronjob
crond

# Start serve
php artisan serve --host=0.0.0.0 & npm run dev

# Finally call command issued to the docker service
exec "$@"
