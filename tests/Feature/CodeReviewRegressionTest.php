<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression tests for defects found during the Phase 9 review.
 */
class CodeReviewRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_renaming_a_product_with_blank_slug_regenerates_it(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create(['name' => 'Old Name']);

        $this->actingAs($admin)->put("/admin/products/{$product->id}", [
            'category_id' => $product->category_id,
            'name' => 'Fresh New Name',
            'slug' => '',
            'price' => $product->price,
            'stock' => $product->stock,
            'status' => 'active',
        ])->assertSessionHasNoErrors();

        $this->assertSame('fresh-new-name', $product->refresh()->slug);
    }

    public function test_accounts_with_orders_cannot_be_deleted(): void
    {
        $user = User::factory()->create();
        Order::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'password'])
            ->assertRedirect('/profile');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertAuthenticatedAs($user);
    }

    public function test_accounts_without_orders_can_still_be_deleted(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->delete('/profile', ['password' => 'password'])->assertRedirect('/');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_banned_users_are_logged_out_of_live_sessions(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk();

        $user->update(['status' => UserStatus::Banned]);

        $this->actingAs($user->fresh())->get('/dashboard')->assertRedirect('/login');
        $this->assertGuest();
    }
}
