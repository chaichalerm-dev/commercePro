<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(CouponType::cases());

        return [
            'code' => strtoupper(Str::random(8)),
            'type' => $type,
            'value' => $type === CouponType::Percent
                ? fake()->randomElement([5, 10, 15, 20])
                : fake()->randomElement([50, 100, 200]),
            'min_order' => fake()->randomElement([0, 500, 1000]),
            'max_uses' => fake()->randomElement([null, 50, 100]),
            'used_count' => 0,
            'starts_at' => now()->subDays(7),
            'expires_at' => now()->addMonths(3),
            'is_active' => true,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subMonths(2),
            'expires_at' => now()->subDay(),
        ]);
    }
}
