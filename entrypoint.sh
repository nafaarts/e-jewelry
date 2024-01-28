#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    echo "installing library! 🚀"
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating env file from env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

php artisan key:generate
php artisan migrate --seed
php artisan cache:clear 

exec docker-php-entrypoint "$@"