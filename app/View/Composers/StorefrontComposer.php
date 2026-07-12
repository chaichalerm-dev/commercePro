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
 *
 * Bound as a singleton (see AppServiceProvider) so $resolved memoizes for
 * the whole request — the composer fires once for the page view and again
 * for the nested <x-storefront-layout> component, and without this the
 * live cart-count query would run twice per request.
 */
class StorefrontComposer
{
    /** @var array<string, mixed>|null */
    private ?array $resolved = null;

    public function __construct(
        private readonly CartService $cart,
    ) {}

    public function compose(View $view): void
    {
        $this->resolved ??= [
            'navCategories' => Cache::remember(
                'storefront.nav-categories',
                now()->addHour(),
                fn () => Category::query()->active()->whereNull('parent_id')->orderBy('sort_order')->get(),
            ),
            'siteName' => (string) Setting::get('site_name', config('app.name')),
            'siteTagline' => (string) Setting::get('tagline', ''),
            'freeShippingMin' => (int) Setting::get('free_shipping_min', 1000),
            'cartCount' => $this->cart->count(),
        ];

        $view->with($this->resolved);
    }
}
