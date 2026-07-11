# ShopSmart 🛒

A production-structured **e-commerce web application** built as a portfolio project — clean architecture, modern UI, and ready to deploy.

![Homepage mockup](docs/design/homepage-mockup.png)

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12 · PHP 8.4 |
| Database | PostgreSQL (Supabase) |
| Frontend | Blade · Tailwind CSS · Alpine.js (hand-built components) |
| Auth | Laravel Breeze (single auth, role-gated: Admin `/admin`, User `/`) |
| Assets | Vite |
| Queue / Cache | Database driver (Redis-ready) |
| Charts / Icons | Chart.js · Heroicons |
| Quality | PHPUnit (94 tests) · PHPStan level 6 · Pint (PSR-12) |

## Features

- 🏪 Storefront: home, catalog with search/filter/sort, product detail (multi-image, variants), categories
- 🛒 Cart (guest session + user, merged on login), wishlist, demo checkout, order history
- 🧑‍💼 Admin: dashboard with analytics, product/category/order/customer/review/coupon/banner management, settings, activity logs
- 🔐 Security: policies, rate limiting, CSRF/XSS/SQL-injection protection, form request validation
- ⚡ Performance: eager loading, pagination, DB indexes, lazy-loaded images, cache-ready structure
- 🔎 SEO: meta tags, Open Graph, sitemap.xml, canonical URLs, Schema.org structured data

## Getting Started

### Requirements

- PHP 8.4 with `pdo_pgsql`, `intl`, `zip`, `gd`, `mbstring` extensions
- Composer 2
- Node.js 20+
- A Supabase project (free tier works)

### Installation

```bash
git clone <repo-url> shopsmart && cd shopsmart
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Fill in your Supabase credentials in `.env` (Dashboard → Project Settings → Database):

```dotenv
DB_CONNECTION=pgsql
DB_HOST=aws-0-<region>.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.<project-ref>
DB_PASSWORD=<your-password>
DB_SSLMODE=require
```

Then:

```bash
php artisan migrate --seed
php artisan storage:link
composer run dev   # serves app + queue worker + logs + vite
```

### Demo Accounts

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | password |
| User | user@example.com | password |

### Quality Tooling

```bash
composer test      # PHPUnit feature + unit suites (SQLite in-memory)
composer analyse   # PHPStan level 6 via Larastan
composer format    # Laravel Pint (PSR-12)
composer quality   # all three in sequence
```

## Documentation

- [Architecture](docs/ARCHITECTURE.md) — layers, patterns, request flow
- [Database](docs/DATABASE.md) — ER diagram and table reference
- [Deployment](docs/DEPLOYMENT.md) — Railway, Render, and VPS guides with a production checklist

## Development Milestones

| Phase | Scope | Status |
|---|---|---|
| 1 | Project Setup & Architecture | ✅ |
| 2 | Authentication & Role Management | ✅ |
| 3 | Database & Models | ✅ |
| 4 | User Interface | ✅ |
| 5 | Admin Dashboard | ✅ |
| 6 | Product Management | ✅ |
| 7 | Cart, Wishlist & Orders | ✅ |
| 8 | Performance & Security | ✅ |
| 9 | Testing & Code Review | ✅ |
| 10 | Deployment & Documentation | ✅ |

## License

MIT
