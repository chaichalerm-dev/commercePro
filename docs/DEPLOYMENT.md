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

Render's auto-detected "native" runtime picks a build/start flow based on which manifest files it sees (`package.json` → Node, in this repo's case) — it has no notion of "PHP" once a Node buildpack is chosen, so a start command like `php artisan serve` fails with `php: command not found`. **Use the Docker environment instead** — this repo's `Dockerfile` already handles the full build (Vite assets + Composer + Apache + queue worker via Supervisor), so Render just needs to be told to use it.

**Recommended: deploy via the included `render.yaml` Blueprint**

1. Render Dashboard → **New → Blueprint**, connect this repo. Render reads `render.yaml` at the repo root and creates the web service with `runtime: docker`, pointed at `./Dockerfile`, automatically — no manual build/start command config at all.
2. Fill in the env vars marked `sync: false` in `render.yaml` (`APP_KEY`, `APP_URL`, `DB_HOST`/`DB_USERNAME`/`DB_PASSWORD`, mail credentials) in the Render dashboard.
3. First deploy: leave `RUN_MIGRATIONS=true` (already the Blueprint's default) so `entrypoint.sh` runs `migrate --force` on boot; flip it to `false` afterwards if you'd rather run migrations manually on future deploys.
4. The queue worker runs inside the same container via Supervisor — no separate Background Worker service needed.

**If you already created the service manually as Native/Node:** Render doesn't let you switch an existing service's environment from Native to Docker after the fact — delete it and create a fresh one (via the Blueprint above, or **New → Web Service** and picking **Docker** as the runtime when prompted), rather than trying to fix the build/start commands on the Node-typed service.

**Scheduler:** add a **Cron Job** service running `php artisan schedule:run` every minute (Render Cron Jobs use the same Docker image, so no extra setup — just override the command).

## VPS (Ubuntu + nginx)

1. Install PHP 8.4 (`ppa:ondrej/php`) with `php8.4-fpm php8.4-pgsql php8.4-intl php8.4-zip php8.4-gd php8.4-mbstring php8.4-curl`, nginx, Node 20, Composer.
2. Clone to `/var/www/shopsmart`, run the deploy recipe, point nginx's root at `/var/www/shopsmart/public` (standard Laravel nginx config).
3. `chown -R www-data:www-data storage bootstrap/cache`
4. Supervisor program for the queue worker; crontab entry for the scheduler.
5. TLS via `certbot --nginx`.

## Uploaded Images on PaaS

Container filesystems are ephemeral — on Render's free tier in particular, every restart or redeploy wipes the local disk, so anything saved under `storage/app/public` via the admin panel (logo, favicon, banner, product/category images) vanishes even though the database still has its path on record. Demo/seed images are unaffected since those are external URLs (picsum), not local files.

The app is set up to use **Supabase Storage** (S3-compatible, same Supabase project already used for the database) as the fix — set `FILESYSTEM_DISK=s3` and fill in the `AWS_*` variables:

1. Supabase Dashboard → **Storage** → create a new bucket, mark it **Public**.
2. Supabase Dashboard → **Project Settings → Storage → S3 Connection** to get the access key ID/secret and the region.
3. Set these env vars (already templated in `render.yaml` with `sync: false`, so Render prompts for them in the dashboard rather than storing them in the repo):
   ```dotenv
   FILESYSTEM_DISK=s3
   AWS_ACCESS_KEY_ID=...
   AWS_SECRET_ACCESS_KEY=...
   AWS_DEFAULT_REGION=ap-southeast-1        # your Supabase project's region
   AWS_BUCKET=your-bucket-name
   AWS_ENDPOINT=https://<project-ref>.supabase.co/storage/v1/s3
   AWS_URL=https://<project-ref>.supabase.co/storage/v1/object/public/your-bucket-name
   AWS_USE_PATH_STYLE_ENDPOINT=true          # required — Supabase doesn't support virtual-hosted-style addressing
   ```

`FILESYSTEM_DISK` is read dynamically everywhere a file gets stored, deleted, or resolved to a URL (`ResolvesImageUrl`, `Setting::url()`, and the admin controllers' upload handling) — flipping this one env var moves all of it between local disk and S3 consistently, no code changes needed.

Alternative: attach a Render **persistent Disk** mounted at `storage/app/public` instead (requires a paid plan, not available on Free) — simpler if you'd rather not touch storage config at all, but ties you to Render specifically rather than a portable S3-compatible target.

## Post-deploy Checklist

- [ ] `APP_DEBUG=false`, `APP_ENV=production`, fresh `APP_KEY`
- [ ] Database password rotated; `DB_SSLMODE=require`
- [ ] `php artisan migrate --force` ran (seed **only** if you want demo data: `php artisan db:seed --force`)
- [ ] Queue worker alive (place a test order → confirmation email sends)
- [ ] Scheduler ticking (`schedule:run` logs)
- [ ] `/robots.txt` and `/sitemap.xml` resolve with the production domain
- [ ] Demo accounts: change or remove `admin@example.com` / `user@example.com` passwords
