<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Enums\BannerPosition;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Support\HomeCache;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    public function index(): View
    {
        return view('storefront.home', [
            'heroBanners' => Cache::remember(HomeCache::HERO_BANNERS, HomeCache::TTL_SECONDS, fn () => Banner::query()->active()->position(BannerPosition::Hero)->get()),
            'promoBanners' => Cache::remember(HomeCache::PROMO_BANNERS, HomeCache::TTL_SECONDS, fn () => Banner::query()->active()->position(BannerPosition::Promo)->get()),
            'featuredProducts' => Cache::remember(HomeCache::FEATURED, HomeCache::TTL_SECONDS, fn () => $this->products->featured(10)),
            'latestProducts' => Cache::remember(HomeCache::LATEST, HomeCache::TTL_SECONDS, fn () => $this->products->latest(8)),
        ]);
    }
}
