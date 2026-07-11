<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_create_a_category(): void
    {
        $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'อุปกรณ์กีฬา',
            'is_active' => 1,
            'sort_order' => 3,
        ])->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', ['name' => 'อุปกรณ์กีฬา', 'sort_order' => 3]);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        Product::factory()->for($category)->create();

        $this->actingAs($this->admin)
            ->from('/admin/categories')
            ->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'deleted_at' => null]);
    }

    public function test_empty_category_can_be_deleted(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)->delete("/admin/categories/{$category->id}");

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_non_admin_cannot_create_categories(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/categories', ['name' => 'Hack'])
            ->assertForbidden();
    }
}
