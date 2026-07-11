<?php

namespace App\Providers;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\EloquentProductRepository;
use App\View\Composers\StorefrontComposer;
use Illuminate\Database\Eloquent\Model;
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

        View::composer(['components.storefront-layout', 'storefront.*'], StorefrontComposer::class);
    }
}
