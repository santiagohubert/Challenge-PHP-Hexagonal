#!/bin/sh
set -e

if [ ! -f .env ]; then
  cp .env.example .env
fi

if [ -z "$APP_KEY" ] && ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

echo "Waiting for database..."
until php artisan db:show > /dev/null 2>&1; do
  sleep 2
done

php artisan migrate --force
php artisan db:seed --force

if [ ! -f storage/oauth-private.key ]; then
  php artisan passport:install --force
fi

exec "$@"
