<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    public function definition(): array
    {
        [$name, $value] = fake()->randomElement([
            ['Size', fake()->randomElement(['S', 'M', 'L', 'XL'])],
            ['Color', fake()->randomElement(['Black', 'White', 'Navy', 'Beige', 'Olive'])],
        ]);

        return [
            'product_id' => Product::factory(),
            'sku' => 'V-'.strtoupper(Str::random(8)),
            'name' => $name,
            'value' => $value,
            'price_modifier' => fake()->randomElement([0, 0, 50, 100, 200]),
            'stock' => fake()->numberBetween(0, 40),
        ];
    }
}
