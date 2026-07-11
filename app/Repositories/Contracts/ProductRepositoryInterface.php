<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Catalog listing: active products with search, category, price-range,
     * on-sale filters and sorting, paginated for the storefront grid.
     *
     * @param  array{q?: string, category?: string, min_price?: numeric, max_price?: numeric, on_sale?: bool, sort?: string}  $filters
     * @return LengthAwarePaginator<int, Product>
     */
    public function paginateCatalog(array $filters, int $perPage = 12): LengthAwarePaginator;

    /**
     * @return Collection<int, Product>
     */
    public function featured(int $limit = 10): Collection;

    /**
     * @return Collection<int, Product>
     */
    public function latest(int $limit = 8): Collection;

    /**
     * @return Collection<int, Product>
     */
    public function relatedTo(Product $product, int $limit = 4): Collection;

    public function findBySlugForStorefront(string $slug): Product;

    public function findCategoryBySlug(string $slug): Category;
}
