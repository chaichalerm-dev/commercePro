<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->admin = User::factory()->admin()->create();
    }

    public function test_non_admins_cannot_manage_products(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin/products')->assertForbidden();
        $this->actingAs($user)->post('/admin/products', [])->assertForbidden();
    }

    public function test_admin_can_create_a_product_with_images_and_variants(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'เสื้อยืดคอตตอน Premium',
            'price' => 590,
            'compare_at_price' => 790,
            'stock' => 25,
            'status' => 'active',
            'featured' => 1,
            'thumbnail' => UploadedFile::fake()->image('thumb.jpg'),
            'images' => [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')],
            'variants' => [
                ['name' => 'Size', 'value' => 'M', 'price_modifier' => 0, 'stock' => 10],
                ['name' => 'Size', 'value' => 'XL', 'price_modifier' => 50, 'stock' => 5],
            ],
        ]);

        $response->assertRedirect('/admin/products');

        $product = Product::query()->firstWhere('name', 'เสื้อยืดคอตตอน Premium');

        $this->assertNotNull($product);
        $this->assertNotEmpty($product->sku);
        $this->assertNotEmpty($product->slug);
        $this->assertCount(2, $product->images);
        $this->assertCount(2, $product->variants);
        Storage::disk('public')->assertExists($product->thumbnail);
        $this->assertDatabaseHas('activity_logs', ['action' => 'product.created', 'user_id' => $this->admin->id]);
    }

    public function test_validation_rejects_compare_price_below_price(): void
    {
        $category = Category::factory()->create();

        $this->actingAs($this->admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Bad Deal',
            'price' => 500,
            'compare_at_price' => 400,
            'stock' => 1,
            'status' => 'active',
        ])->assertSessionHasErrors('compare_at_price');
    }

    public function test_update_syncs_variants_and_removes_selected_images(): void
    {
        $product = Product::factory()->create();
        $keep = $product->variants()->create(['sku' => 'V-KEEP', 'name' => 'Size', 'value' => 'M', 'price_modifier' => 0, 'stock' => 3]);
        $drop = $product->variants()->create(['sku' => 'V-DROP', 'name' => 'Size', 'value' => 'L', 'price_modifier' => 0, 'stock' => 3]);
        $image = $product->images()->create(['path' => 'products/old.jpg', 'sort_order' => 0]);

        $this->actingAs($this->admin)->put("/admin/products/{$product->id}", [
            'category_id' => $product->category_id,
            'name' => $product->name,
            'price' => $product->price,
            'stock' => 9,
            'status' => 'active',
            'removed_images' => [$image->id],
            'variants' => [
                ['id' => $keep->id, 'name' => 'Size', 'value' => 'M', 'price_modifier' => 20, 'stock' => 7],
                ['name' => 'Color', 'value' => 'Black', 'price_modifier' => 0, 'stock' => 4],
            ],
        ])->assertRedirect('/admin/products');

        $product->refresh();

        $this->assertSame(9, $product->stock);
        $this->assertCount(2, $product->variants);
        $this->assertSame(20.0, (float) $keep->refresh()->price_modifier);
        $this->assertNull($product->variants()->find($drop->id));
        $this->assertDatabaseMissing('product_images', ['id' => $image->id]);
    }

    public function test_admin_can_soft_delete_and_restore_a_product(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin)->delete("/admin/products/{$product->id}");
        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $this->actingAs($this->admin)->patch("/admin/products/{$product->id}/restore");
        $this->assertDatabaseHas('products', ['id' => $product->id, 'deleted_at' => null]);
    }

    public function test_soft_deleted_products_disappear_from_storefront(): void
    {
        $product = Product::factory()->create();

        $this->actingAs($this->admin)->delete("/admin/products/{$product->id}");

        $this->get('/products')->assertDontSee($product->name);
        $this->get("/products/{$product->slug}")->assertNotFound();
    }

    public function test_featured_toggle_flips_the_flag(): void
    {
        $product = Product::factory()->create(['featured' => false]);

        $response = $this->actingAs($this->admin)->patch("/admin/products/{$product->id}/toggle-featured");
        $response->assertRedirect();

        $this->assertTrue($product->refresh()->featured);
    }
}
