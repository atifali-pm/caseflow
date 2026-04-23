#!/usr/bin/env bash
# CaseFlow production entrypoint.
#
# Runs on every container start (Render, Fly, any PaaS). Migrates on every boot
# so schema stays in sync, but only seeds when the database is empty — so a
# restart doesn't wipe user-created data.

set -e

cd /var/www/html

echo "==> Running migrations"
php artisan migrate --force

# Seed only on first boot (empty users table). We query the DB directly via
# tinker so this works before any app caches are warmed.
USER_COUNT=$(php artisan tinker --execute='echo App\Models\User::count();' 2>/dev/null | tr -d '[:space:]')

if [ -z "$USER_COUNT" ] || [ "$USER_COUNT" = "0" ]; then
    echo "==> First boot detected, seeding demo data"
    php artisan db:seed --force
else
    echo "==> Users table has $USER_COUNT row(s), skipping seed"
fi

echo "==> Caching config, routes, and views"
php artisan config:cache
php artisan route:cache
php artisan view:cache

PORT="${PORT:-8000}"
echo "==> Starting server on 0.0.0.0:${PORT}"
exec php artisan serve --host=0.0.0.0 --port="${PORT}"
