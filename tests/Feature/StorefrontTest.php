<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_shows_featured_products_and_banners(): void
    {
        $banner = Banner::factory()->create(['title' => 'ดีลสุดคุ้มประจำสัปดาห์']);
        $product = Product::factory()->featured()->create();

        $this->get('/')
            ->assertOk()
            ->assertSee($banner->title)
            ->assertSee($product->name);
    }

    public function test_products_page_lists_active_products_only(): void
    {
        $active = Product::factory()->create();
        $draft = Product::factory()->draft()->create();

        $this->get('/products')
            ->assertOk()
            ->assertSee($active->name)
            ->assertDontSee($draft->name);
    }

    public function test_products_can_be_searched_by_name(): void
    {
        $match = Product::factory()->create(['name' => 'Wireless Headphones Pro']);
        $other = Product::factory()->create(['name' => 'Cotton T-Shirt']);

        $this->get('/products?q=wireless')
            ->assertOk()
            ->assertSee($match->name)
            ->assertDontSee($other->name);
    }

    public function test_products_can_be_filtered_by_price_and_sale(): void
    {
        $cheap = Product::factory()->create(['name' => 'Budget Mouse', 'price' => 100, 'compare_at_price' => null]);
        $expensive = Product::factory()->create(['name' => 'Premium Keyboard', 'price' => 5000, 'compare_at_price' => 6000]);

        $this->get('/products?max_price=500')->assertOk()->assertSee('Budget Mouse')->assertDontSee('Premium Keyboard');
        $this->get('/products?on_sale=1')->assertOk()->assertSee('Premium Keyboard')->assertDontSee('Budget Mouse');
    }

    public function test_product_detail_page_renders(): void
    {
        $product = Product::factory()->create();

        $this->get("/products/{$product->slug}")
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee($product->sku);
    }

    public function test_draft_product_detail_returns_404(): void
    {
        $draft = Product::factory()->draft()->create();

        $this->get("/products/{$draft->slug}")->assertNotFound();
    }

    public function test_category_page_lists_only_its_products(): void
    {
        $category = Category::factory()->create();
        $inside = Product::factory()->for($category)->create();
        $outside = Product::factory()->create();

        $this->get("/category/{$category->slug}")
            ->assertOk()
            ->assertSee($inside->name)
            ->assertDontSee($outside->name);
    }

    public function test_static_pages_render(): void
    {
        $this->get('/about')->assertOk();
        $this->get('/contact')->assertOk();
    }

    public function test_unknown_page_returns_custom_404(): void
    {
        $this->get('/no-such-page')
            ->assertNotFound()
            ->assertSee('ไม่พบหน้าที่คุณต้องการ');
    }
}
