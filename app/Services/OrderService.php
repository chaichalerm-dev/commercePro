<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ProductStatus;
use App\Mail\OrderPlacedMail;
use App\Models\ActivityLog;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private readonly CartService $cart,
    ) {}

    /**
     * Turn the user's cart into an order: validates stock under row locks,
     * snapshots prices, applies the coupon, decrements stock, clears the
     * cart, and queues the confirmation email. Everything or nothing.
     */
    public function placeFromCart(User $user, Address $address, ?string $couponCode = null): Order
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => __('storefront/checkout.errors.cart_empty')]);
        }

        $order = DB::transaction(function () use ($user, $address, $couponCode, $items): Order {
            $subtotal = 0.0;
            $lines = [];

            foreach ($items as $item) {
                /** @var Product $product locked so concurrent checkouts cannot oversell */
                $product = Product::query()->whereKey($item->product_id)->lockForUpdate()->firstOrFail();
                $variant = $item->product_variant_id !== null
                    ? $product->variants()->whereKey($item->product_variant_id)->lockForUpdate()->first()
                    : null;

                if ($product->status !== ProductStatus::Active) {
                    throw ValidationException::withMessages(['cart' => __('storefront/checkout.errors.product_unavailable', ['name' => $product->name])]);
                }

                $available = $variant !== null ? min($variant->stock, $product->stock) : $product->stock;

                if ($item->qty > $available) {
                    throw ValidationException::withMessages(['cart' => __('storefront/checkout.errors.insufficient_stock', ['name' => $product->name, 'available' => $available])]);
                }

                $modifier = $variant !== null ? (float) $variant->price_modifier : 0.0;
                $unitPrice = round((float) $product->price + $modifier, 2);
                $lineTotal = round($unitPrice * $item->qty, 2);
                $subtotal += $lineTotal;

                $lines[] = [
                    'product' => $product,
                    'variant' => $variant,
                    'qty' => $item->qty,
                    'price' => $unitPrice,
                    'total' => $lineTotal,
                ];
            }

            $coupon = $this->resolveCoupon($couponCode, $subtotal);
            $discount = $coupon?->discountFor($subtotal) ?? 0.0;
            $shipping = $this->shippingFee($subtotal);

            $order = Order::query()->create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'coupon_id' => $coupon?->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping' => $shipping,
                'tax' => 0,
                'grand_total' => round($subtotal - $discount + $shipping, 2),
                'status' => OrderStatus::Pending,
                'payment_status' => PaymentStatus::Unpaid,
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'product_variant_id' => $line['variant']?->id,
                    'product_name' => $line['product']->name.($line['variant'] !== null ? " ({$line['variant']->name}: {$line['variant']->value})" : ''),
                    'qty' => $line['qty'],
                    'price' => $line['price'],
                    'total' => $line['total'],
                ]);

                $line['product']->decrement('stock', $line['qty']);
                $line['variant']?->decrement('stock', $line['qty']);
            }

            $coupon?->increment('used_count');
            $this->cart->clear();

            ActivityLog::record('order.placed', $order, ['order_number' => $order->order_number, 'grand_total' => (float) $order->grand_total]);

            return $order;
        });

        Mail::to($user)->locale(app()->getLocale())->queue(new OrderPlacedMail($order));

        return $order;
    }

    /**
     * Cancel an order and put the stock back. Used by both the customer
     * (while cancellable) and the admin status flow.
     */
    public function cancel(Order $order): Order
    {
        if (! $order->isCancellable()) {
            throw ValidationException::withMessages(['status' => __('storefront/checkout.errors.order_not_cancellable')]);
        }

        return DB::transaction(function () use ($order): Order {
            foreach ($order->items()->with(['product', 'variant'])->get() as $item) {
                $item->product?->increment('stock', $item->qty);
                $item->variant?->increment('stock', $item->qty);
            }

            $order->update([
                'status' => OrderStatus::Cancelled,
                'payment_status' => $order->payment_status === PaymentStatus::Paid ? PaymentStatus::Refunded : $order->payment_status,
            ]);

            ActivityLog::record('order.cancelled', $order, ['order_number' => $order->order_number]);

            return $order;
        });
    }

    /**
     * Admin-driven status change, validated against the allowed transitions.
     */
    public function transition(Order $order, OrderStatus $to): Order
    {
        if (! in_array($to, $order->status->transitions(), true)) {
            throw ValidationException::withMessages([
                'status' => __('storefront/checkout.errors.invalid_status_transition', ['from' => $order->status->label(), 'to' => $to->label()]),
            ]);
        }

        if ($to === OrderStatus::Cancelled) {
            return $this->cancel($order);
        }

        $order->update(['status' => $to]);

        ActivityLog::record('order.status_changed', $order, ['to' => $to->value]);

        return $order;
    }

    public function updatePaymentStatus(Order $order, PaymentStatus $status): Order
    {
        $order->update(['payment_status' => $status]);

        ActivityLog::record('order.payment_changed', $order, ['to' => $status->value]);

        return $order;
    }

    protected function resolveCoupon(?string $code, float $subtotal): ?Coupon
    {
        if (blank($code)) {
            return null;
        }

        $coupon = Coupon::query()->where('code', $code)->lockForUpdate()->first();

        if ($coupon === null || ! $coupon->isRedeemable($subtotal)) {
            throw ValidationException::withMessages(['coupon' => __('storefront/checkout.errors.coupon_not_applicable')]);
        }

        return $coupon;
    }

    protected function shippingFee(float $subtotal): float
    {
        $freeMin = (float) Setting::get('free_shipping_min', 1000);

        return $subtotal >= $freeMin ? 0.0 : (float) Setting::get('shipping_fee', 50);
    }
}
