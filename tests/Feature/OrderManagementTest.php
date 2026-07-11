<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_only_see_their_own_orders(): void
    {
        $owner = User::factory()->create();
        $order = Order::factory()->for($owner)->create();

        $this->actingAs($owner)->get("/orders/{$order->id}")->assertOk();
        $this->actingAs(User::factory()->create())->get("/orders/{$order->id}")->assertForbidden();
    }

    public function test_cancelling_an_order_restocks_products(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5]);
        $order = Order::factory()->for($user)->create(['status' => OrderStatus::Pending]);
        $order->items()->create(['product_id' => $product->id, 'product_name' => $product->name, 'qty' => 3, 'price' => 100, 'total' => 300]);

        $this->actingAs($user)->post("/orders/{$order->id}/cancel");

        $this->assertSame(OrderStatus::Cancelled, $order->refresh()->status);
        $this->assertSame(8, $product->refresh()->stock);
    }

    public function test_delivered_orders_cannot_be_cancelled_by_the_user(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->for($user)->create(['status' => OrderStatus::Delivered]);

        $this->actingAs($user)->post("/orders/{$order->id}/cancel")->assertForbidden();
    }

    public function test_admin_can_move_order_through_valid_transitions(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);

        $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", ['status' => 'processing']);
        $this->assertSame(OrderStatus::Processing, $order->refresh()->status);

        $this->actingAs($admin)->patch("/admin/orders/{$order->id}/status", ['status' => 'shipped']);
        $this->assertSame(OrderStatus::Shipped, $order->refresh()->status);
    }

    public function test_invalid_status_transition_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $order = Order::factory()->create(['status' => OrderStatus::Pending]);

        $this->actingAs($admin)
            ->patch("/admin/orders/{$order->id}/status", ['status' => 'delivered'])
            ->assertSessionHasErrors('status');

        $this->assertSame(OrderStatus::Pending, $order->refresh()->status);
    }

    public function test_wishlist_toggle_adds_and_removes(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post('/wishlist/toggle', ['product_id' => $product->id]);
        $this->assertDatabaseHas('wishlists', ['user_id' => $user->id, 'product_id' => $product->id]);

        $this->actingAs($user)->post('/wishlist/toggle', ['product_id' => $product->id]);
        $this->assertDatabaseMissing('wishlists', ['user_id' => $user->id, 'product_id' => $product->id]);
    }

    public function test_wishlist_requires_login(): void
    {
        $this->get('/wishlist')->assertRedirect('/login');
    }
}
