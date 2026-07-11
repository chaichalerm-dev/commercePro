<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $products,
    ) {}

    public function show(Request $request, string $slug): View
    {
        $category = $this->products->findCategoryBySlug($slug);

        $filters = [
            'q' => $request->query('q'),
            'category' => $category->slug,
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'on_sale' => $request->boolean('on_sale'),
            'sort' => (string) $request->query('sort', 'latest'),
        ];

        return view('storefront.products.index', [
            'products' => $this->products->paginateCatalog($filters),
            'filters' => $filters,
            'category' => $category,
        ]);
    }
}
