# Database Design

PostgreSQL on Supabase. All tables use `id` (bigint identity), `created_at`/`updated_at`; catalog and order tables add `deleted_at` (soft delete). Money columns are `numeric(12,2)` — never float.

## ER Diagram

```mermaid
erDiagram
    roles ||--o{ users : "has many"
    users ||--o{ orders : places
    users ||--o{ addresses : has
    users ||--o{ reviews : writes
    users ||--o{ wishlists : keeps
    users ||--o{ cart_items : holds
    users ||--o{ notifications : receives
    users ||--o{ activity_logs : triggers

    categories ||--o{ products : contains
    categories ||--o{ categories : "parent of"
    products ||--o{ product_images : has
    products ||--o{ product_variants : has
    products ||--o{ reviews : receives
    products ||--o{ wishlists : "wished in"
    products ||--o{ cart_items : "carted in"
    products ||--o{ order_items : "sold as"

    orders ||--o{ order_items : contains
    orders }o--|| addresses : "ships to"
    coupons ||--o{ orders : "applied to"

    roles { bigint id PK; varchar name UK; varchar label }
    users { bigint id PK; bigint role_id FK; varchar name; varchar email UK; varchar phone; varchar password; varchar avatar; varchar status; timestamptz email_verified_at }
    categories { bigint id PK; bigint parent_id FK; varchar name; varchar slug UK; varchar image; boolean is_active; int sort_order }
    products { bigint id PK; bigint category_id FK; varchar sku UK; varchar slug UK; varchar name; text description; numeric price; numeric compare_at_price; int stock; varchar thumbnail; varchar status; boolean featured }
    product_images { bigint id PK; bigint product_id FK; varchar path; int sort_order }
    product_variants { bigint id PK; bigint product_id FK; varchar sku UK; varchar name; varchar value; numeric price_modifier; int stock }
    orders { bigint id PK; varchar order_number UK; bigint user_id FK; bigint address_id FK; bigint coupon_id FK; numeric subtotal; numeric discount; numeric shipping; numeric tax; numeric grand_total; varchar status; varchar payment_status }
    order_items { bigint id PK; bigint order_id FK; bigint product_id FK; bigint product_variant_id FK; varchar product_name; int qty; numeric price; numeric total }
    addresses { bigint id PK; bigint user_id FK; varchar label; varchar recipient; varchar phone; varchar line1; varchar district; varchar province; varchar postal_code; boolean is_default }
    reviews { bigint id PK; bigint user_id FK; bigint product_id FK; smallint rating; text comment; boolean is_approved }
    wishlists { bigint id PK; bigint user_id FK; bigint product_id FK }
    cart_items { bigint id PK; bigint user_id FK; varchar session_id; bigint product_id FK; bigint product_variant_id FK; int qty }
    coupons { bigint id PK; varchar code UK; varchar type; numeric value; numeric min_order; int max_uses; int used_count; timestamptz starts_at; timestamptz expires_at; boolean is_active }
    banners { bigint id PK; varchar title; varchar subtitle; boolean show_title; varchar image; varchar link; varchar position; int sort_order; boolean is_active }
    notifications { uuid id PK; varchar type; bigint notifiable_id; jsonb data; timestamptz read_at }
    settings { bigint id PK; varchar key UK; text value; varchar group }
    activity_logs { bigint id PK; bigint user_id FK; varchar action; varchar subject_type; bigint subject_id; jsonb properties; varchar ip_address }
```

Framework tables (`password_reset_tokens`, `sessions`, `jobs`, `job_batches`, `failed_jobs`, `cache`, `cache_locks`) use Laravel's stock migrations.

The whole schema above (app tables + framework tables) lives in a single migration file, `database/migrations/0001_01_01_000000_create_shopsmart_schema.php`, consolidated from what was originally 20 separate per-table migrations so a fresh install is one `php artisan migrate` away from a working schema.

## Design Notes

| Decision | Reasoning |
|---|---|
| `roles` as a table (not enum column) | Spec requires it; allows labels/permissions later. `role_id` FK on users with an index. |
| `order_items.product_name` + `price` snapshot | Orders must stay historically correct even if the product is renamed/repriced/deleted. |
| `cart_items.session_id` nullable + `user_id` nullable | Supports guest carts; on login rows merge into the user's cart (unique on `(user_id, product_id, product_variant_id)`). |
| `compare_at_price` on products | Powers the "-20% ฿1,990 ~~฿2,490~~" pattern from the mockup without computing fake discounts. |
| `settings` as key/value with `group` | Site name, logo, contact, socials editable from admin without migrations. |
| `banners.position` | One table drives hero sliders and promo banners (mockup has both). |
| jsonb for logs/notifications | Flexible payloads, queryable in Postgres. |

## Indexing Plan (applied in migrations)

- FKs: every `*_id` column
- `products`: `(status, featured)`, `slug`, `sku`, `category_id`, price (for sort)
- `orders`: `order_number`, `(user_id, created_at)`, `status`
- `cart_items`: `session_id`, unique composite per owner+product+variant
- `reviews`: unique `(user_id, product_id)` — one review per user per product
- `wishlists`: unique `(user_id, product_id)`
- `settings.key`, `coupons.code`: unique
