<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function paginateCatalog(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        return $this->storefrontQuery()
            ->when(filled($filters['q'] ?? null), function (Builder $query) use ($filters): void {
                $term = trim((string) $filters['q']);
                // ilike is Postgres-only; sqlite's like is already case-insensitive.
                $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $query->whereAny(['name', 'description', 'sku'], $operator, "%{$term}%");
            })
            ->when(filled($filters['category'] ?? null), fn (Builder $query) => $query
                ->whereHas('category', fn (Builder $sub) => $sub->where('slug', $filters['category'])))
            ->when(is_numeric($filters['min_price'] ?? null), fn (Builder $query) => $query
                ->where('price', '>=', (float) $filters['min_price']))
            ->when(is_numeric($filters['max_price'] ?? null), fn (Builder $query) => $query
                ->where('price', '<=', (float) $filters['max_price']))
            ->when(($filters['on_sale'] ?? false), fn (Builder $query) => $query
                ->whereNotNull('compare_at_price')->whereColumn('compare_at_price', '>', 'price'))
            ->tap(fn (Builder $query) => $this->applySort($query, $filters['sort'] ?? 'latest'))
            ->paginate($perPage)
            ->withQueryString();
    }

    public function featured(int $limit = 10): Collection
    {
        return $this->storefrontQuery()->featured()->latest()->limit($limit)->get();
    }

    public function latest(int $limit = 8): Collection
    {
        return $this->storefrontQuery()->latest()->limit($limit)->get();
    }

    public function relatedTo(Product $product, int $limit = 4): Collection
    {
        return $this->storefrontQuery()
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public function findBySlugForStorefront(string $slug): Product
    {
        return Product::query()
            ->active()
            ->with(['category', 'images', 'variants'])
            ->withAvg('approvedReviews as rating_avg', 'rating')
            ->withCount('approvedReviews as reviews_count')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function findCategoryBySlug(string $slug): Category
    {
        return Category::query()->active()->where('slug', $slug)->firstOrFail();
    }

    /**
     * Base query every storefront listing shares: active products with the
     * category eager-loaded and review aggregates for the rating stars.
     *
     * @return Builder<Product>
     */
    protected function storefrontQuery(): Builder
    {
        return Product::query()
            ->active()
            ->with('category')
            ->withAvg('approvedReviews as rating_avg', 'rating')
            ->withCount('approvedReviews as reviews_count');
    }

    /**
     * @param  Builder<Product>  $query
     */
    protected function applySort(Builder $query, string $sort): void
    {
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'popular' => $query->orderByDesc('reviews_count')->orderByDesc('rating_avg'),
            default => $query->latest(),
        };
    }
}
