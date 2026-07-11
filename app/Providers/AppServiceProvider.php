<?php

namespace App\Providers;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\EloquentProductRepository;
use App\View\Composers\StorefrontComposer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
    }
}
