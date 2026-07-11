<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Mail\OrderPlacedMail;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        $this->user = User::factory()->create();
    }

    private function newAddressPayload(): array
    {
        return [
            'recipient' => 'สมชาย ใจดี',
            'phone' => '081-234-5678',
            'line1' => '99/1 ถนนสุขุมวิท',
            'district' => 'วัฒนา',
            'province' => 'กรุงเทพมหานคร',
            'postal_code' => '10110',
        ];
    }

    public function test_placing_an_order_creates_it_and_decrements_stock(): void
    {
        $product = Product::factory()->create(['price' => 1500, 'stock' => 10]);
        CartItem::factory()->for($this->user)->for($product)->create(['qty' => 2]);

        $response = $this->actingAs($this->user)->post('/checkout', $this->newAddressPayload());

        $order = Order::query()->firstWhere('user_id', $this->user->id);

        $response->assertRedirect(route('orders.show', $order));
        $this->assertSame(3000.0, (float) $order->subtotal);
        $this->assertSame(0.0, (float) $order->shipping); // over the free-shipping minimum
        $this->assertSame(3000.0, (float) $order->grand_total);
        $this->assertSame(8, $product->refresh()->stock);
        $this->assertSame(0, CartItem::count());
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'product_name' => $product->name, 'qty' => 2]);
        Mail::assertQueued(OrderPlacedMail::class);
    }

    public function test_shipping_fee_applies_below_the_free_minimum(): void
    {
        $product = Product::factory()->create(['price' => 200, 'stock' => 5]);
        CartItem::factory()->for($this->user)->for($product)->create(['qty' => 1]);

        $this->actingAs($this->user)->post('/checkout', $this->newAddressPayload());

        $order = Order::query()->firstWhere('user_id', $this->user->id);

        $this->assertSame(50.0, (float) $order->shipping);
        $this->assertSame(250.0, (float) $order->grand_total);
    }

    public function test_coupon_discount_is_applied_and_usage_counted(): void
    {
        $product = Product::factory()->create(['price' => 2000, 'stock' => 5]);
        CartItem::factory()->for($this->user)->for($product)->create(['qty' => 1]);
        $coupon = Coupon::factory()->create(['code' => 'WELCOME10', 'type' => 'percent', 'value' => 10, 'min_order' => 0]);

        $this->actingAs($this->user)
            ->withSession(['checkout.coupon' => 'WELCOME10'])
            ->post('/checkout', $this->newAddressPayload());

        $order = Order::query()->firstWhere('user_id', $this->user->id);

        $this->assertSame(200.0, (float) $order->discount);
        $this->assertSame(1800.0, (float) $order->grand_total);
        $this->assertSame(1, $coupon->refresh()->used_count);
        $this->assertSame($coupon->id, $order->coupon_id);
    }

    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        $product = Product::factory()->create(['stock' => 1]);
        $item = CartItem::factory()->for($this->user)->for($product)->create(['qty' => 1]);
        // Someone else buys the last unit before this user checks out.
        $product->update(['stock' => 0]);

        $this->actingAs($this->user)
            ->post('/checkout', $this->newAddressPayload())
            ->assertSessionHasErrors('cart');

        $this->assertSame(0, Order::count());
        $this->assertDatabaseHas('cart_items', ['id' => $item->id]);
    }

    public function test_checkout_with_empty_cart_redirects_back(): void
    {
        $this->actingAs($this->user)
            ->get('/checkout')
            ->assertRedirect('/cart');
    }
}
