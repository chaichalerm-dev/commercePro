<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    private const FREE_SHIPPING_MIN = 1000;

    private const SHIPPING_FEE = 50;

    /**
     * 20 orders with real line items and consistent totals. The demo user
     * gets the first eight so their order history page has content.
     */
    public function run(): void
    {
        $demoUser = User::query()->where('email', 'user@example.com')->firstOrFail();
        $customers = User::factory(8)->create();
        $products = Product::query()->inStock()->get();
        $coupons = Coupon::query()->where('is_active', true)->whereDate('expires_at', '>', now())->get();

        foreach ($customers->push($demoUser) as $customer) {
            Address::factory()->default()->for($customer)->create();
        }

        for ($i = 0; $i < 20; $i++) {
            $user = $i < 8 ? $demoUser : $customers->random();

            $order = Order::factory()->for($user)->create([
                'address_id' => $user->addresses()->first()->id,
            ]);

            $subtotal = 0.0;

            foreach ($products->random(fake()->numberBetween(1, 4)) as $product) {
                $qty = fake()->numberBetween(1, 3);
                $total = round((float) $product->price * $qty, 2);
                $subtotal += $total;

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'qty' => $qty,
                    'price' => $product->price,
                    'total' => $total,
                ]);
            }

            $discount = 0.0;
            $coupon = null;

            if ($coupons->isNotEmpty() && fake()->boolean(30)) {
                $candidate = $coupons->random();

                if ($candidate->isRedeemable($subtotal)) {
                    $coupon = $candidate;
                    $discount = $coupon->discountFor($subtotal);
                    $coupon->increment('used_count');
                }
            }

            $shipping = $subtotal >= self::FREE_SHIPPING_MIN ? 0 : self::SHIPPING_FEE;

            $order->update([
                'coupon_id' => $coupon?->id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping' => $shipping,
                'grand_total' => round($subtotal - $discount + $shipping, 2),
            ]);
        }
    }
}
