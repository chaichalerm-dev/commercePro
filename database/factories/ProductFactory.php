<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = ucwords(fake()->unique()->words(3, true));
        // Round prices to x9 endings so cards look like real retail (฿1,290).
        $price = fake()->numberBetween(9, 4999) * 10;
        $onSale = fake()->boolean(40);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'description' => fake()->paragraphs(3, true),
            'price' => $price,
            'compare_at_price' => $onSale ? (int) round($price * fake()->randomFloat(2, 1.1, 1.5), -1) : null,
            'stock' => fake()->numberBetween(0, 120),
            'thumbnail' => 'https://picsum.photos/seed/'.Str::slug($name).'/600/600',
            'status' => ProductStatus::Active,
            'featured' => fake()->boolean(20),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['status' => ProductStatus::Draft]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['featured' => true]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => ['stock' => 0]);
    }
}
