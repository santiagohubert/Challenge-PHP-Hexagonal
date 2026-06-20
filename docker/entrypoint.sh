#!/bin/sh
set -e

if [ ! -f .env ]; then
  cp .env.example .env
fi

if [ -z "$APP_KEY" ] && ! grep -q '^APP_KEY=base64:' .env; then
  php artisan key:generate --force
fi

echo "Waiting for database..."
until php -r "
  try {
    new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306') . ';dbname=' . getenv('DB_DATABASE'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD')
    );
    exit(0);
  } catch (Throwable \$e) {
    exit(1);
  }
" > /dev/null 2>&1; do
  sleep 2
done

php artisan migrate --force
php artisan db:seed --force

php artisan passport:keys --force

php artisan passport:client \
  --personal \
  --name="${APP_NAME:-Challenge PHP Hexagonal}" \
  --no-interaction 2>/dev/null || true

chown www-data:www-data storage/oauth-private.key storage/oauth-public.key 2>/dev/null || true
chmod 640 storage/oauth-private.key storage/oauth-public.key 2>/dev/null || true

exec "$@"
