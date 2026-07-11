<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_a_product_to_the_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->post('/cart', ['product_id' => $product->id, 'qty' => 2])
            ->assertRedirect('/cart');

        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'qty' => 2, 'user_id' => null]);
        $this->get('/cart')->assertOk()->assertSee($product->name);
    }

    public function test_adding_the_same_product_merges_quantities(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->post('/cart', ['product_id' => $product->id, 'qty' => 2]);
        $this->post('/cart', ['product_id' => $product->id, 'qty' => 3]);

        $this->assertSame(1, CartItem::count());
        $this->assertSame(5, CartItem::first()->qty);
    }

    public function test_cart_quantity_is_capped_at_available_stock(): void
    {
        $product = Product::factory()->create(['stock' => 3]);

        $this->post('/cart', ['product_id' => $product->id, 'qty' => 99]);

        $this->assertSame(3, CartItem::first()->qty);
    }

    public function test_out_of_stock_products_cannot_be_added(): void
    {
        $product = Product::factory()->outOfStock()->create();

        $this->post('/cart', ['product_id' => $product->id, 'qty' => 1])
            ->assertSessionHasErrors('qty');

        $this->assertSame(0, CartItem::count());
    }

    public function test_guest_cart_merges_into_account_on_login(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->post('/cart', ['product_id' => $product->id, 'qty' => 2]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password']);

        $this->assertDatabaseHas('cart_items', ['user_id' => $user->id, 'product_id' => $product->id, 'qty' => 2]);
        $this->assertSame(0, CartItem::whereNull('user_id')->count());
    }

    public function test_users_cannot_touch_other_users_cart_items(): void
    {
        $owner = User::factory()->create();
        $item = CartItem::factory()->for($owner)->create();

        $this->actingAs(User::factory()->create())
            ->delete("/cart/{$item->id}")
            ->assertNotFound();

        $this->assertDatabaseHas('cart_items', ['id' => $item->id]);
    }
}
