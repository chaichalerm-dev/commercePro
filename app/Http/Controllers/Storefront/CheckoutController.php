<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\CheckoutRequest;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Setting;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private const COUPON_SESSION_KEY = 'checkout.coupon';

    public function __construct(
        private readonly CartService $cart,
        private readonly OrderService $orders,
    ) {}

    public function show(Request $request): View|RedirectResponse
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('storefront/checkout.flash.cart_empty'));
        }

        $subtotal = $this->cart->subtotal();
        $coupon = $this->sessionCoupon($subtotal);
        $freeShippingMin = (float) Setting::get('free_shipping_min', 1000);
        $shipping = $subtotal >= $freeShippingMin ? 0.0 : (float) Setting::get('shipping_fee', 50);
        $discount = $coupon?->discountFor($subtotal) ?? 0.0;

        return view('storefront.checkout.index', [
            'items' => $items,
            'addresses' => $request->user()->addresses()->orderByDesc('is_default')->get(),
            'subtotal' => $subtotal,
            'coupon' => $coupon,
            'discount' => $discount,
            'shipping' => $shipping,
            'grandTotal' => round($subtotal - $discount + $shipping, 2),
        ]);
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate(['code' => ['required', 'string', 'max:30']]);

        $coupon = Coupon::query()->where('code', strtoupper(trim($validated['code'])))->first();

        if ($coupon === null || ! $coupon->isRedeemable($this->cart->subtotal())) {
            return back()->with('error', __('storefront/checkout.flash.coupon_invalid'));
        }

        session()->put(self::COUPON_SESSION_KEY, $coupon->code);

        return back()->with('success', __('storefront/checkout.flash.coupon_applied', ['code' => $coupon->code]));
    }

    public function removeCoupon(): RedirectResponse
    {
        session()->forget(self::COUPON_SESSION_KEY);

        return back()->with('success', __('storefront/checkout.flash.coupon_removed'));
    }

    public function place(CheckoutRequest $request): RedirectResponse
    {
        $address = $this->resolveAddress($request);

        $order = $this->orders->placeFromCart(
            $request->user(),
            $address,
            session()->get(self::COUPON_SESSION_KEY),
        );

        session()->forget(self::COUPON_SESSION_KEY);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', __('storefront/checkout.flash.order_placed', ['number' => $order->order_number]));
    }

    protected function resolveAddress(CheckoutRequest $request): Address
    {
        if ($request->filled('address_id')) {
            return $request->user()->addresses()->findOrFail($request->integer('address_id'));
        }

        return $request->user()->addresses()->create([
            ...$request->safe()->only(['recipient', 'phone', 'line1', 'district', 'province', 'postal_code']),
            'label' => __('storefront/checkout.default_address_label'),
            'is_default' => ! $request->user()->addresses()->exists(),
        ]);
    }

    protected function sessionCoupon(float $subtotal): ?Coupon
    {
        $code = session()->get(self::COUPON_SESSION_KEY);

        if (blank($code)) {
            return null;
        }

        $coupon = Coupon::query()->where('code', $code)->first();

        if ($coupon === null || ! $coupon->isRedeemable($subtotal)) {
            session()->forget(self::COUPON_SESSION_KEY);

            return null;
        }

        return $coupon;
    }
}
