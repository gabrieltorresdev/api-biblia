#!/bin/bash

set -e

cd /var/www

cp -n .env.example .env && \
composer install && \
php artisan key:generate && \
php artisan migrate && \
php artisan optimize:clear && \
supervisord -n -c /etc/supervisord.conf &

exec "$@"

