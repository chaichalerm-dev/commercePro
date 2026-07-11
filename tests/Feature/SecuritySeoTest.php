<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecuritySeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present_on_responses(): void
    {
        $this->get('/login')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_robots_txt_disallows_admin_and_links_sitemap(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap:');
    }

    public function test_sitemap_lists_active_products_and_categories(): void
    {
        $category = Category::factory()->create();
        $active = Product::factory()->for($category)->create();
        $draft = Product::factory()->draft()->create();

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('products.show', $active->slug), false)
            ->assertSee(route('categories.show', $category->slug), false)
            ->assertDontSee(route('products.show', $draft->slug), false);
    }

    public function test_checkout_is_rate_limited(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 10) as $i) {
            $this->actingAs($user)->post('/checkout', []);
        }

        $this->actingAs($user)->post('/checkout', [])->assertTooManyRequests();
    }
}
