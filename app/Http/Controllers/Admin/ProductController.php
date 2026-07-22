<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ProductStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service,
    ) {}

    public function index(Request $request): View
    {
        $showTrash = $request->query('view') === 'trash';

        $products = Product::query()
            ->when($showTrash, fn ($query) => $query->onlyTrashed())
            ->with('category')
            ->when(filled($request->query('q')), function ($query) use ($request): void {
                $term = trim((string) $request->query('q'));
                $operator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $query->whereAny(['name', 'sku'], $operator, "%{$term}%");
            })
            ->when(filled($request->query('category')), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when(filled($request->query('status')), fn ($query) => $query->where('status', $request->query('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
            'statuses' => ProductStatus::cases(),
            'showTrash' => $showTrash,
            'trashCount' => Product::onlyTrashed()->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::query()->orderBy('name')->get(),
            'statuses' => ProductStatus::cases(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = $this->service->create(
            $request->safe()->except(['thumbnail', 'images', 'variants']),
            $request->file('thumbnail'),
            $request->file('images', []),
            $request->validated('variants', []),
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('admin/products.flash.created', ['name' => $product->name]));
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product->load(['images', 'variants']),
            'categories' => Category::query()->orderBy('name')->get(),
            'statuses' => ProductStatus::cases(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->service->update(
            $product,
            $request->safe()->except(['thumbnail', 'images', 'variants', 'removed_images']),
            $request->file('thumbnail'),
            $request->file('images', []),
            $request->validated('variants', []),
            array_map(intval(...), $request->validated('removed_images', [])),
        );

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('admin/products.flash.updated', ['name' => $product->name]));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->service->delete($product);

        return back()->with('success', __('admin/products.flash.deleted', ['name' => $product->name]));
    }

    public function restore(Product $product): RedirectResponse
    {
        $this->service->restore($product);

        return back()->with('success', __('admin/products.flash.restored', ['name' => $product->name]));
    }

    public function toggleFeatured(Product $product): RedirectResponse
    {
        $product = $this->service->toggleFeatured($product);

        return back()->with('success', $product->featured
            ? __('admin/products.flash.featured_on', ['name' => $product->name])
            : __('admin/products.flash.featured_off', ['name' => $product->name]));
    }
}
