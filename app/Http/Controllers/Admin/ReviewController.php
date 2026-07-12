<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reviews = Review::query()
            ->with(['user', 'product'])
            ->when($request->query('status') === 'pending', fn ($query) => $query->where('is_approved', false))
            ->when($request->query('status') === 'approved', fn ($query) => $query->where('is_approved', true))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reviews.index', ['reviews' => $reviews]);
    }

    public function toggle(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        ActivityLog::record('review.moderated', $review, ['approved' => $review->is_approved, 'product' => $review->product->name]);

        return back()->with('success', $review->is_approved ? __('admin/reviews.flash.approved') : __('admin/reviews.flash.hidden'));
    }

    public function destroy(Review $review): RedirectResponse
    {
        $product = $review->product->name;

        $review->delete();

        ActivityLog::record('review.deleted', $review, ['product' => $product]);

        return back()->with('success', __('admin/reviews.flash.deleted'));
    }
}
