<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Enums\BannerPosition;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    public function index(): View
    {
        return view('storefront.home', [
            'heroBanners' => Banner::query()->active()->position(BannerPosition::Hero)->get(),
            'promoBanners' => Banner::query()->active()->position(BannerPosition::Promo)->get(),
            'featuredProducts' => $this->products->featured(10),
            'latestProducts' => $this->products->latest(8),
        ]);
    }
}
