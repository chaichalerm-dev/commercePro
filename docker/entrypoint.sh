#!/bin/bash
set -e

# Railway/Render inject PORT and expect the container to listen on it —
# Apache's default config is hardcoded to 80, so rewrite both files that
# reference it before starting.
port="${PORT:-80}"
sed -i "s/Listen 80/Listen ${port}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${port}>/" /etc/apache2/sites-available/000-default.conf

if [ ! -f /var/www/html/.env ] && [ -f /var/www/html/.env.example ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Config/route/view caches are keyed off the real runtime env, which only
# exists once the platform injects it — so this has to happen at container
# start, not at image build time.
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link 2>/dev/null || true

# Opt-in: platforms that run a single instance can set RUN_MIGRATIONS=true.
# Left off by default so a redeploy never runs migrations unattended.
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    php artisan migrate --force
fi

exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
