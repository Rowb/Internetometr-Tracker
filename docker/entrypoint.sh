#!/bin/bash
set -e

if [ ! -f /config/www/artisan ]; then
    echo "Initializing application in /config/www ..."
    cp -a /defaults/www/. /config/www/
fi

cd /config/www

if [ ! -f .env ]; then
    cp .env.example .env
    sed -i 's|^DB_DATABASE=.*|DB_DATABASE=/config/www/database/speed.db|' .env
    sed -i 's|^QUEUE_CONNECTION=.*|QUEUE_CONNECTION=sync|' .env
    sed -i 's|^APP_ENV=.*|APP_ENV=production|' .env
    sed -i 's|^APP_DEBUG=.*|APP_DEBUG=false|' .env
fi

mkdir -p database storage/framework/{cache,sessions,views} storage/logs bootstrap/cache
touch database/speed.db
chown -R www-data:www-data /config/www
chmod -R u+rwX,g+rwX /config/www/storage /config/www/bootstrap/cache /config/www/database

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

if ! grep -qE '^JWT_SECRET=.+' .env; then
    php artisan jwt:secret --force
fi

php artisan migrate --force
php artisan config:clear
php artisan cache:clear

printf '%s\n' '* * * * * www-data cd /config/www && php artisan schedule:run >> /config/log/speedtest/cron.log 2>&1' '' > /etc/cron.d/speedtest
chmod 0644 /etc/cron.d/speedtest
cron

exec apache2-foreground
