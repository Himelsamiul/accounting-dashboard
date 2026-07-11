#!/usr/bin/env bash
#
# Server-side deploy script for Prime Byte Accounting (Hostinger / hPanel).
# Runs on every push to `main` via .github/workflows/deploy.yml (over SSH).
# You can also run it by hand after `ssh` into the server:  bash deploy.sh
#
set -euo pipefail

echo "==> Deploy started: $(date)"

# 1. Get the latest code (discard any local server-side changes to avoid conflicts)
git fetch --all
git reset --hard origin/main

# 2. PHP dependencies (production only, optimised autoloader)
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 3. Put the app in maintenance mode during migration (auto-lifted at the end)
php artisan down --retry=15 || true

# 4. Database migrations (never seeds in production)
php artisan migrate --force

# 5. Refresh cached config / routes / views for production speed
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache || true

# 6. Ensure the public storage symlink exists (safe to re-run)
php artisan storage:link || true

# 7. Bring the site back up
php artisan up

echo "==> Deploy finished: $(date)"
