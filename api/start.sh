#!/bin/bash
set -e

cp .env.example .env

php artisan key:generate --ansi

# Generate Swagger API docs
php artisan l5-swagger:generate

# Start apache in the foreground
apache2-foreground
