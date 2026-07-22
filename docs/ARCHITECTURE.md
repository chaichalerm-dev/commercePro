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
| Support | `app/Support` | Tiny global formatting helpers (`money()`) plus small self-contained utilities (`ImageOptimizer` — resize/re-encode uploads) | Keep minimal; promote to a Service if it grows business logic |
| Views | `resources/views` (`layouts/`, `components/`, `storefront/`, `admin/`) | Presentation | Blade components for reuse |

## Key Decisions

### Single auth, two entry points
One Breeze auth system; `role` on `users` gates access. Admin routes live under `/admin` behind an `admin` middleware → non-admins get **403**. This avoids duplicated guards/sessions while keeping surfaces separate (matches the mockup's User Login / Admin Login tabs).

### Repository pattern — selectively
A full repository-per-model adds indirection without value in Laravel. We use repositories only where query logic is genuinely complex and reused (product catalog search/filter/sort). Everything else uses Eloquent directly through services.

### Policies vs Gates — only write a Policy for per-instance logic
Every `/admin` route is already wrapped in a `can:<ability>` Gate (`routes/admin.php`, defined in `AppServiceProvider::configureAdminGates()`), which is a pure role check (`$user->role_id->can($ability)`) — it has no access to *which row* is being touched. That's the right tool for "can this role reach this admin section at all," and it's enforced at the route level, so it actually blocks the request rather than just hiding UI.

A Policy (`app/Policies`) is only worth adding on top when authorization genuinely depends on the specific record or the acting user's relationship to it — something the route-level Gate structurally cannot express. `OrderPolicy` is the one real example: a storefront customer may view/cancel *their own* order, which depends on `$order->user_id` and the order's current status, not on role alone.

Admin resource controllers (Product, Category, Banner, Coupon, User, Review, Setting) intentionally have **no** Policy and **no** `$this->authorize()` calls — the route Gate is the only check, and adding a Policy that just re-checks `$user->isAdmin()` would be pure duplication with no behavior difference (this project did exactly that for Product/Category early on and removed it once the redundancy was noticed). If a future admin resource needs row-level rules (e.g. "Staff can edit their own draft products but not others'"), that's the signal to add a real Policy for it — not a habit to apply uniformly "for consistency."

### Database queue & cache, Redis-ready
`QUEUE_CONNECTION=database`, `CACHE_STORE=database` work everywhere (free tiers included). Swapping to Redis later is a `.env` change only — no code changes, because all caching goes through Laravel's `Cache` facade with named keys.

### UI: hand-built Tailwind + Alpine components (no UI kit)
The original plan weighed Flowbite against DaisyUI, but every interactive piece the mockup needed — hero slider, dropdowns, image gallery, dynamic variant rows, toasts — turned out to be a few lines of Alpine each. Building them directly kept the bundle lean (no kit CSS/JS shipped to visitors) and matched the mockup exactly instead of approximately.

### Environments
`.env` (local, debug on, mail → log) vs production (debug off, real SMTP, `config:cache`/`route:cache`/`view:cache` in deploy step). All environment-specific values come from `.env` — never hardcoded.

## Conventions

- **PSR-12**, enforced by Pint (`vendor/bin/pint`)
- `declare(strict_types=1);` + parameter/return types on all app code
- PHP 8.4 features where they clarify: enums, readonly, property hooks
- Eager loading required on list pages (N+1 guarded via `Model::preventLazyLoading()` in non-production)
- Soft deletes on catalog/order data; activity log records admin mutations
