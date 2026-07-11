<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_slug_is_generated_and_unique(): void
    {
        $first = Product::factory()->create(['name' => 'Wireless Headphones', 'slug' => null]);
        $second = Product::factory()->create(['name' => 'Wireless Headphones', 'slug' => null]);

        $this->assertSame('wireless-headphones', $first->slug);
        $this->assertSame('wireless-headphones-2', $second->slug);
    }

    public function test_product_sku_is_generated_when_missing(): void
    {
        $product = Product::factory()->create(['sku' => null]);

        $this->assertMatchesRegularExpression('/^P\d{6}-[A-Z0-9]{6}$/', $product->sku);
    }

    public function test_order_number_is_generated_when_missing(): void
    {
        $order = Order::factory()->create();

        $this->assertMatchesRegularExpression('/^ORD-\d{8}-[A-Z0-9]{5}$/', $order->order_number);
    }

    public function test_product_discount_percent_is_derived_from_compare_at_price(): void
    {
        $product = Product::factory()->create(['price' => 800, 'compare_at_price' => 1000]);
        $fullPrice = Product::factory()->create(['price' => 800, 'compare_at_price' => null]);

        $this->assertSame(20, $product->discount_percent);
        $this->assertNull($fullPrice->discount_percent);
    }

    public function test_user_has_commerce_relationships(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create();

        $this->assertTrue($user->orders->first()->is($order));
    }
}
