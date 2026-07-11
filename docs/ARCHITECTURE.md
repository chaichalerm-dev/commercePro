# Architecture

ShopSmart follows a **layered MVC + Service architecture** — pragmatic clean architecture that keeps Laravel idiomatic while separating concerns.

## Request Flow

```
Route → Middleware → Controller → Form Request (validation)
                        │
                        ▼
                    Service (business logic, transactions)
                        │
                        ▼
        Eloquent Model ── or ── Repository (only where queries are complex)
                        │
                        ▼
                  PostgreSQL (Supabase)
```

Responses go back through **API Resources / Blade views**, with flash messages and toasts for UX feedback.

## Layers & Responsibilities

| Layer | Location | Responsibility | Rule of thumb |
|---|---|---|---|
| Routes | `routes/web.php`, `routes/admin.php` | URL → controller mapping, middleware groups | No logic in routes |
| Middleware | `app/Http/Middleware` | Auth, role gate (admin), rate limiting | Cross-cutting only |
| Controllers | `app/Http/Controllers` (`Storefront/`, `Admin/`) | HTTP orchestration only | ≤ ~7 lines per action |
| Form Requests | `app/Http/Requests` | Validation + authorization of input | Every write endpoint has one |
| Services | `app/Services` | Business logic, DB transactions, events | One service per domain (Cart, Order, Product…) |
| Repositories | `app/Repositories` | Complex/reusable query encapsulation | **Only** where Eloquent alone gets messy (e.g. product catalog filtering). Simple CRUD talks to models directly — KISS |
| Models | `app/Models` | Relationships, casts, scopes, accessors | No business logic |
| Policies | `app/Policies` | Authorization per model | Checked in controllers/requests |
| Enums | `app/Enums` | Type-safe statuses (`OrderStatus`, `PaymentStatus`, `UserRole`, `ProductStatus`) | No hardcoded status strings anywhere |
| Traits | `app/Traits` | Shared model behavior (`HasSlug`, `LogsActivity`) | DRY across models |
| Helpers | `app/Support/helpers.php` | Tiny global formatting helpers (`money()`) | Keep minimal |
| Views | `resources/views` (`layouts/`, `components/`, `storefront/`, `admin/`) | Presentation | Blade components for reuse |

## Key Decisions

### Single auth, two entry points
One Breeze auth system; `role` on `users` gates access. Admin routes live under `/admin` behind an `admin` middleware → non-admins get **403**. This avoids duplicated guards/sessions while keeping surfaces separate (matches the mockup's User Login / Admin Login tabs).

### Repository pattern — selectively
A full repository-per-model adds indirection without value in Laravel. We use repositories only where query logic is genuinely complex and reused (product catalog search/filter/sort). Everything else uses Eloquent directly through services.

### Database queue & cache, Redis-ready
`QUEUE_CONNECTION=database`, `CACHE_STORE=database` work everywhere (free tiers included). Swapping to Redis later is a `.env` change only — no code changes, because all caching goes through Laravel's `Cache` facade with named keys.

### UI kit: Flowbite over DaisyUI
Flowbite is Tailwind-utility-first, pairs naturally with Alpine.js, and ships e-commerce blocks (product cards, mega menus) that match the ShopSmart mockup. DaisyUI's semantic classes would fight the custom orange/white design tokens.

### Environments
`.env` (local, debug on, mail → log) vs production (debug off, real SMTP, `config:cache`/`route:cache`/`view:cache` in deploy step). All environment-specific values come from `.env` — never hardcoded.

## Conventions

- **PSR-12**, enforced by Pint (`vendor/bin/pint`)
- `declare(strict_types=1);` + parameter/return types on all app code
- PHP 8.4 features where they clarify: enums, readonly, property hooks
- Eager loading required on list pages (N+1 guarded via `Model::preventLazyLoading()` in non-production)
- Soft deletes on catalog/order data; activity log records admin mutations
