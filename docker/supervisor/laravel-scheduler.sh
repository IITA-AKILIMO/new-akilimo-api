#!/bin/bash
# /usr/local/bin/laravel-scheduler.sh

while [ true ]
do
  php /var/www/html/akilimo/artisan schedule:run --verbose --no-interaction &
  sleep 60
done
