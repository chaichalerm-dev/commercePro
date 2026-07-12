<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\CouponType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\ActivityLog;
use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CouponController extends Controller
{
    public function index(): View
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::query()->withCount('orders')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.coupons.create', ['types' => CouponType::cases()]);
    }

    public function store(CouponRequest $request): RedirectResponse
    {
        $coupon = Coupon::query()->create($request->validated());

        ActivityLog::record('coupon.created', $coupon, ['code' => $coupon->code]);

        return redirect()->route('admin.coupons.index')->with('success', __('admin/coupons.flash.created', ['code' => $coupon->code]));
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', ['coupon' => $coupon, 'types' => CouponType::cases()]);
    }

    public function update(CouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $coupon->update($request->validated());

        ActivityLog::record('coupon.updated', $coupon, ['code' => $coupon->code]);

        return redirect()->route('admin.coupons.index')->with('success', __('admin/coupons.flash.updated', ['code' => $coupon->code]));
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        if ($coupon->orders()->exists()) {
            $coupon->update(['is_active' => false]);

            return back()->with('error', __('admin/coupons.flash.delete_blocked', ['code' => $coupon->code]));
        }

        $coupon->delete();

        ActivityLog::record('coupon.deleted', $coupon, ['code' => $coupon->code]);

        return back()->with('success', __('admin/coupons.flash.deleted', ['code' => $coupon->code]));
    }
}
