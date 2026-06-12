#!/usr/bin/env bash
set -euo pipefail

if [ "${1:-}" != "" ] && [ "${1}" != "php-fpm" ]; then
    exec "$@"
fi

PORT="${PORT:-8080}"

sed "s/\${PORT}/${PORT}/g" /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

mkdir -p /run/php /var/www/storage/framework/cache /var/www/storage/framework/sessions /var/www/storage/framework/views
chown -R www-data:www-data /run/php /var/www/storage /var/www/bootstrap/cache /var/www/database

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ] && [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
    chown www-data:www-data /var/www/database/database.sqlite
fi

php-fpm -D
exec nginx -g 'daemon off;'
