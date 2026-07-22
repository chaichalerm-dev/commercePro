<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Support\ImageOptimizer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);

        return view('admin.categories.index', [
            'categories' => Category::query()->withCount('products')->orderBy('sort_order')->paginate(15),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::query()->create($this->payload($request));

        ActivityLog::record('category.created', $category, ['name' => $category->name]);
        $this->forgetNavCache();

        return redirect()->route('admin.categories.index')->with('success', __('admin/categories.flash.created', ['name' => $category->name]));
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);

        $category->update($this->payload($request, $category));

        ActivityLog::record('category.updated', $category, ['name' => $category->name]);
        $this->forgetNavCache();

        return redirect()->route('admin.categories.index')->with('success', __('admin/categories.flash.updated', ['name' => $category->name]));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        if ($category->products()->withTrashed()->exists()) {
            return back()->with('error', __('admin/categories.flash.delete_blocked', ['name' => $category->name]));
        }

        $category->delete();

        ActivityLog::record('category.deleted', $category, ['name' => $category->name]);
        $this->forgetNavCache();

        return back()->with('success', __('admin/categories.flash.deleted', ['name' => $category->name]));
    }

    /**
     * @return array<string, mixed>
     */
    protected function payload(CategoryRequest $request, ?Category $category = null): array
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $old = $category?->image;

            if (filled($old) && ! Str::startsWith($old, ['http://', 'https://'])) {
                Storage::disk(config('filesystems.default'))->delete($old);
            }

            $data['image'] = ImageOptimizer::store($request->file('image'), 'categories', config('filesystems.default'), maxWidth: 800, maxHeight: 800);
        }

        return $data;
    }

    protected function forgetNavCache(): void
    {
        Cache::forget('storefront.nav-categories');
    }
}
