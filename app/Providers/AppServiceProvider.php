<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\EloquentProductRepository;
use App\Support\PostgresConnection;
use App\View\Composers\StorefrontComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->singleton(StorefrontComposer::class);

        // See App\Support\PostgresConnection: PDO::ATTR_EMULATE_PREPARES
        // (config/database.php) needs boolean bindings kept as real Postgres
        // literals instead of Laravel's default int cast.
        Connection::resolverFor('pgsql', fn ($connection, $database, $prefix, $config) => new PostgresConnection($connection, $database, $prefix, $config));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Surface N+1 queries and typos as exceptions during development;
        // in production these silently fall back to lazy loading.
        Model::shouldBeStrict(! $this->app->isProduction());

        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        View::composer(['components.storefront-layout', 'storefront.*'], StorefrontComposer::class);

        $this->configureRateLimiting();
        $this->configureAdminGates();
    }

    /**
     * One Gate per admin-panel section, resolved against the user's role tier
     * (see App\Enums\UserRole::can()). Used both as route middleware
     * (`can:<ability>`) and to hide sidebar links the current admin can't reach.
     */
    protected function configureAdminGates(): void
    {
        foreach ([
            'products', 'orders', 'reviews',
            'categories', 'coupons', 'banners', 'customers', 'logs',
            'users', 'settings',
        ] as $ability) {
            Gate::define($ability, fn (User $user): bool => $user->role_id->can($ability));
        }
    }

    protected function configureRateLimiting(): void
    {
        $keyFor = fn (Request $request): string => $request->user() !== null
            ? 'user:'.$request->user()->id
            : 'ip:'.$request->ip();

        // Cart mutations: generous but bot-resistant.
        RateLimiter::for('cart', fn (Request $request) => Limit::perMinute(30)->by($keyFor($request)));

        // Order placement: tight — a human never needs more.
        RateLimiter::for('checkout', fn (Request $request) => Limit::perMinute(10)->by($keyFor($request)));

        // Coupon apply: tight enough to make code-guessing impractical, still
        // generous for someone genuinely retyping a code they mistyped.
        RateLimiter::for('coupon', fn (Request $request) => Limit::perMinute(10)->by($keyFor($request)));
    }
}
