# Deployment Guide

ShopSmart runs anywhere PHP 8.4 does. The database is already remote (Supabase), so the app tier is stateless apart from `storage/app/public` uploads.

## Production Environment Variables

```dotenv
APP_NAME=ShopSmart
APP_ENV=production
APP_KEY=            # php artisan key:generate --show, paste the value
APP_DEBUG=false     # NEVER true in production
APP_URL=https://your-domain.com
APP_TIMEZONE=Asia/Bangkok

LOG_CHANNEL=stack
LOG_STACK=stderr    # PaaS platforms collect stdout/stderr
LOG_LEVEL=warning

DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-south-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.<project-ref>
DB_PASSWORD=<rotate the password before going live>
DB_SSLMODE=require

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=public

MAIL_MAILER=smtp    # e.g. Resend, Mailgun, Postmark
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=no-reply@your-domain.com
```

> ⚠️ Rotate the Supabase database password before the first production deploy, and never commit `.env`.

## The Deploy Recipe (any platform)

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Two long-running processes are required:

| Process | Command |
|---|---|
| Web | `php artisan serve --host=0.0.0.0 --port=$PORT` (PaaS) or php-fpm + nginx (VPS) |
| Queue worker | `php artisan queue:work --tries=3 --max-time=3600` |

Plus one cron entry for the scheduler:

```
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

## Docker

A production `Dockerfile` is included (multi-stage: Node/Vite build → `php:8.4-apache` runtime, with Supervisor running Apache and a `queue:work` process side by side). It's a better fit for platforms that deploy from a container image (Railway, Render, Fly.io, a VPS) than the raw `php artisan serve` recipe above, since Apache handles real concurrent connections — `php artisan serve` is single-threaded on Windows dev machines and not meant for production regardless of OS.

```bash
docker build -t shopsmart:prod .
docker run -d -p 8080:80 --env-file .env -e RUN_MIGRATIONS=true shopsmart:prod
```

Key points:

- The entrypoint (`docker/entrypoint.sh`) rewrites Apache's listen port from `$PORT` (Railway/Render inject this), runs `config:cache`/`route:cache`/`view:cache`/`storage:link` at container start (not build time, since these depend on runtime env), and only runs migrations if `RUN_MIGRATIONS=true` is set — left off by default so a redeploy never runs migrations unattended.
- `.env` is never baked into the image (`.dockerignore` excludes it) — inject real env vars via the platform's secret/variable manager, or `--env-file .env` for local testing.
- The queue worker runs inside the same container via Supervisor (`docker/supervisor/supervisord.conf`), so no second service is required for queued mail like order confirmations.
- `compose.yaml` (Laravel Sail) is a **separate, local-dev-only** setup — it is not meant for production and is not what the `Dockerfile` above uses.

## Railway

1. **New Project → Deploy from GitHub repo** — Railway's Nixpacks detects Laravel automatically.
2. Set the variables above in **Variables** (generate `APP_KEY` locally).
3. Custom start command: `php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=$PORT`
4. Add a second service from the same repo for the queue worker with start command `php artisan queue:work --tries=3`.
5. Scheduler: add a service running `php artisan schedule:work` (keeps a lightweight loop instead of cron).

## Render

1. **New → Web Service**, connect the repo, runtime **PHP**.
2. Build command: `composer install --no-dev --optimize-autoloader && npm ci && npm run build`
3. Start command: `php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=$PORT`
4. Add a **Background Worker** with `php artisan queue:work --tries=3` and a **Cron Job** running `php artisan schedule:run` every minute.
5. Attach a persistent disk mounted at `storage/app/public` if you rely on local uploads.

## VPS (Ubuntu + nginx)

1. Install PHP 8.4 (`ppa:ondrej/php`) with `php8.4-fpm php8.4-pgsql php8.4-intl php8.4-zip php8.4-gd php8.4-mbstring php8.4-curl`, nginx, Node 20, Composer.
2. Clone to `/var/www/shopsmart`, run the deploy recipe, point nginx's root at `/var/www/shopsmart/public` (standard Laravel nginx config).
3. `chown -R www-data:www-data storage bootstrap/cache`
4. Supervisor program for the queue worker; crontab entry for the scheduler.
5. TLS via `certbot --nginx`.

## Uploaded Images on PaaS

Container filesystems are ephemeral. Demo/seed images are external URLs and unaffected, but admin uploads need either a persistent disk (Render) / volume (Railway) mounted over `storage/app/public`, or a swap of `FILESYSTEM_DISK` to an S3-compatible bucket (Supabase Storage works) — the `ResolvesImageUrl` trait already handles absolute URLs, so no view changes are needed.

## Post-deploy Checklist

- [ ] `APP_DEBUG=false`, `APP_ENV=production`, fresh `APP_KEY`
- [ ] Database password rotated; `DB_SSLMODE=require`
- [ ] `php artisan migrate --force` ran (seed **only** if you want demo data: `php artisan db:seed --force`)
- [ ] Queue worker alive (place a test order → confirmation email sends)
- [ ] Scheduler ticking (`schedule:run` logs)
- [ ] `/robots.txt` and `/sitemap.xml` resolve with the production domain
- [ ] Demo accounts: change or remove `admin@example.com` / `user@example.com` passwords
