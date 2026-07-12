<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BannerPosition;
use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => ucwords(fake()->words(3, true)),
            'subtitle' => fake()->sentence(),
            'show_title' => true,
            'image' => 'https://picsum.photos/seed/'.fake()->unique()->uuid().'/1600/600',
            'link' => '/products',
            'position' => BannerPosition::Hero,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function promo(): static
    {
        return $this->state(fn (array $attributes) => ['position' => BannerPosition::Promo]);
    }
}
