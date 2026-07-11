<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'session_id' => null,
            'product_id' => Product::factory(),
            'product_variant_id' => null,
            'qty' => fake()->numberBetween(1, 3),
        ];
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => null,
            'session_id' => fake()->sha1(),
        ]);
    }
}
