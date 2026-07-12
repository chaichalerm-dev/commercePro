<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleHierarchyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_reach_every_admin_section(): void
    {
        $owner = User::factory()->owner()->create();

        foreach (['/admin', '/admin/products', '/admin/orders', '/admin/reviews', '/admin/categories', '/admin/coupons', '/admin/banners', '/admin/customers', '/admin/users', '/admin/settings', '/admin/logs'] as $url) {
            $this->actingAs($owner)->get($url)->assertOk();
        }
    }

    public function test_admin_tier_is_blocked_from_users_and_settings_but_reaches_store_operations(): void
    {
        $admin = User::factory()->staffAdmin()->create();

        foreach (['/admin', '/admin/products', '/admin/orders', '/admin/reviews', '/admin/categories', '/admin/coupons', '/admin/banners', '/admin/customers', '/admin/logs'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        foreach (['/admin/users', '/admin/settings'] as $url) {
            $this->actingAs($admin)->get($url)->assertForbidden();
        }
    }

    public function test_staff_tier_only_reaches_day_to_day_sections(): void
    {
        $staff = User::factory()->staff()->create();

        foreach (['/admin', '/admin/products', '/admin/orders', '/admin/reviews'] as $url) {
            $this->actingAs($staff)->get($url)->assertOk();
        }

        foreach (['/admin/categories', '/admin/coupons', '/admin/banners', '/admin/customers', '/admin/users', '/admin/settings', '/admin/logs'] as $url) {
            $this->actingAs($staff)->get($url)->assertForbidden();
        }
    }

    public function test_only_owner_can_change_a_users_role_or_ban_status(): void
    {
        $customer = User::factory()->create();
        $admin = User::factory()->staffAdmin()->create();
        $staff = User::factory()->staff()->create();

        $this->actingAs($admin)->patch("/admin/users/{$customer->id}/role", ['role_id' => UserRole::Admin->value])->assertForbidden();
        $this->actingAs($admin)->patch("/admin/users/{$customer->id}/toggle-ban")->assertForbidden();
        $this->actingAs($staff)->patch("/admin/users/{$customer->id}/role", ['role_id' => UserRole::Admin->value])->assertForbidden();

        $this->assertSame(UserRole::User, $customer->refresh()->role_id);
        $this->assertFalse($customer->refresh()->isBanned());
    }

    public function test_a_customer_can_never_be_promoted_through_registration_or_self_service(): void
    {
        $customer = User::factory()->create();

        $this->assertFalse($customer->isAdmin());
        $this->assertSame(0, $customer->role_id->level());
    }
}
