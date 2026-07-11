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

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service,
    ) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Product::class);

        $showTrash = $request->query('view') === 'trash';

        $products = Product::query()
            ->when($showTrash, fn ($query) => $query->onlyTrashed())
            ->with('category')
            ->when(filled($request->query('q')), function ($query) use ($request): void {
                $term = trim((string) $request->query('q'));
                $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
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
        $this->authorize('create', Product::class);

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
            ->with('success', "เพิ่มสินค้า \"{$product->name}\" เรียบร้อยแล้ว");
    }

    public function edit(Product $product): View
    {
        $this->authorize('update', $product);

        return view('admin.products.edit', [
            'product' => $product->load(['images', 'variants']),
            'categories' => Category::query()->orderBy('name')->get(),
            'statuses' => ProductStatus::cases(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

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
            ->with('success', "บันทึกสินค้า \"{$product->name}\" เรียบร้อยแล้ว");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        $this->service->delete($product);

        return back()->with('success', "ย้ายสินค้า \"{$product->name}\" ไปถังขยะแล้ว");
    }

    public function restore(Product $product): RedirectResponse
    {
        $this->authorize('restore', $product);

        $this->service->restore($product);

        return back()->with('success', "กู้คืนสินค้า \"{$product->name}\" เรียบร้อยแล้ว");
    }

    public function toggleFeatured(Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $product = $this->service->toggleFeatured($product);

        return back()->with('success', $product->featured
            ? "ตั้ง \"{$product->name}\" เป็นสินค้าแนะนำแล้ว"
            : "นำ \"{$product->name}\" ออกจากสินค้าแนะนำแล้ว");
    }
}
