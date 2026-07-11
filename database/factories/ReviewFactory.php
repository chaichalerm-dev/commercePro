<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'rating' => fake()->numberBetween(3, 5),
            'comment' => fake()->sentences(2, true),
            'is_approved' => true,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => ['is_approved' => false]);
    }
}
