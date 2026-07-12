<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_ban_a_user_but_not_themselves(): void
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)->patch("/admin/users/{$user->id}/toggle-ban");
        $this->assertTrue($user->refresh()->isBanned());

        $this->actingAs($this->admin)
            ->from('/admin/users')
            ->patch("/admin/users/{$this->admin->id}/toggle-ban")
            ->assertSessionHas('error');
        $this->assertFalse($this->admin->refresh()->isBanned());
    }

    public function test_admin_can_promote_a_user_but_not_change_own_role(): void
    {
        $user = User::factory()->create();

        $this->actingAs($this->admin)->patch("/admin/users/{$user->id}/role", ['role_id' => UserRole::Admin->value]);
        $this->assertSame(UserRole::Admin, $user->refresh()->role_id);

        $this->actingAs($this->admin)
            ->patch("/admin/users/{$this->admin->id}/role", ['role_id' => UserRole::User->value])
            ->assertSessionHas('error');
        $this->assertSame(UserRole::Owner, $this->admin->refresh()->role_id);
    }

    public function test_unapproved_reviews_are_hidden_until_moderated(): void
    {
        $product = Product::factory()->create();
        $review = Review::factory()->pending()->for($product)->create(['comment' => 'รอตรวจสอบความคิดเห็นนี้']);

        $this->get("/products/{$product->slug}")->assertDontSee($review->comment);

        $this->actingAs($this->admin)->patch("/admin/reviews/{$review->id}/toggle");

        $this->get("/products/{$product->slug}")->assertSee($review->comment);
    }

    public function test_used_coupons_are_deactivated_instead_of_deleted(): void
    {
        $coupon = Coupon::factory()->create();
        Order::factory()->create(['coupon_id' => $coupon->id]);

        $this->actingAs($this->admin)->delete("/admin/coupons/{$coupon->id}")->assertSessionHas('error');

        $this->assertDatabaseHas('coupons', ['id' => $coupon->id, 'is_active' => false]);
    }

    public function test_settings_update_persists_and_busts_the_cache(): void
    {
        Setting::set('site_name', 'Old Name');
        $this->assertSame('Old Name', Setting::get('site_name'));

        $this->actingAs($this->admin)->patch('/admin/settings', [
            'site_name' => 'ShopSmart Updated',
            'free_shipping_min' => 1500,
            'shipping_fee' => 60,
            'currency' => 'THB',
        ])->assertSessionHas('success');

        $this->assertSame('ShopSmart Updated', Setting::get('site_name'));
        $this->assertSame('1500', Setting::get('free_shipping_min'));
    }

    public function test_non_admins_cannot_reach_any_new_admin_section(): void
    {
        $user = User::factory()->create();

        foreach (['/admin/customers', '/admin/users', '/admin/reviews', '/admin/coupons', '/admin/banners', '/admin/settings'] as $url) {
            $this->actingAs($user)->get($url)->assertForbidden();
        }
    }
}
