<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    public function index(Request $request): View
    {
        return view('storefront.products.index', [
            'products' => $this->products->paginateCatalog($this->filters($request)),
            'filters' => $this->filters($request),
        ]);
    }

    public function show(string $slug): View
    {
        $product = $this->products->findBySlugForStorefront($slug);
        $product->loadMissing(['reviews' => fn ($query) => $query->approved()->with('user')->latest()->limit(10)]);

        return view('storefront.products.show', [
            'product' => $product,
            'relatedProducts' => $this->products->relatedTo($product),
        ]);
    }

    /**
     * @return array{q: ?string, category: ?string, min_price: ?string, max_price: ?string, on_sale: bool, sort: string}
     */
    protected function filters(Request $request): array
    {
        return [
            'q' => $request->query('q'),
            'category' => $request->query('category'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'on_sale' => $request->boolean('on_sale'),
            'sort' => (string) $request->query('sort', 'latest'),
        ];
    }
}
