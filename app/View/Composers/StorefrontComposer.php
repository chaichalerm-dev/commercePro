<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Models\Category;
use App\Models\Setting;
use App\Services\CartService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Supplies every storefront view (layout + pages) with the shared shell
 * data: the category menu (cached), site settings, and the cart badge.
 */
class StorefrontComposer
{
    public function __construct(
        private readonly CartService $cart,
    ) {}

    public function compose(View $view): void
    {
        // once() memoises for the current request — the composer fires for
        // the layout and the page view, but the queries run a single time.
        $view->with(once(fn (): array => [
            'navCategories' => Cache::remember(
                'storefront.nav-categories',
                now()->addHour(),
                fn () => Category::query()->active()->whereNull('parent_id')->orderBy('sort_order')->get(),
            ),
            'siteName' => (string) Setting::get('site_name', config('app.name')),
            'siteTagline' => (string) Setting::get('tagline', ''),
            'freeShippingMin' => (int) Setting::get('free_shipping_min', 1000),
            'cartCount' => $this->cart->count(),
        ]));
    }
}
