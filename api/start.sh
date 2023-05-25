#!/bin/bash
set -e

# Generate Swagger API docs
php artisan l5-swagger:generate

php artisan key:generate

# Start apache in the foreground
apache2-foreground
