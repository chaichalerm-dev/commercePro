<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WishlistController extends Controller
{
    public function index(Request $request): View
    {
        $wishlists = $request->user()
            ->wishlists()
            ->with(['product' => fn ($query) => $query
                ->with('category')
                ->withAvg('approvedReviews as rating_avg', 'rating')
                ->withCount('approvedReviews as reviews_count')])
            ->latest()
            ->paginate(12);

        return view('storefront.wishlist.index', ['wishlists' => $wishlists]);
    }

    public function toggle(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', Rule::exists('products', 'id')->whereNull('deleted_at')],
        ]);

        $existing = $request->user()->wishlists()->where('product_id', $validated['product_id'])->first();

        if ($existing !== null) {
            $existing->delete();

            return back()->with('success', 'นำสินค้าออกจากรายการโปรดแล้ว');
        }

        Wishlist::query()->create([
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
        ]);

        return back()->with('success', 'เพิ่มสินค้าในรายการโปรดแล้ว');
    }
}
