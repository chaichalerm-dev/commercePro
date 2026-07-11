<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'label' => fake()->randomElement(['Home', 'Office']),
            'recipient' => fake()->name(),
            'phone' => fake()->numerify('08#-###-####'),
            'line1' => fake()->buildingNumber().' '.fake()->streetName(),
            'district' => fake()->randomElement(['Bang Rak', 'Chatuchak', 'Lat Phrao', 'Watthana', 'Huai Khwang', 'Phaya Thai']),
            'province' => fake()->randomElement(['Bangkok', 'Nonthaburi', 'Pathum Thani', 'Chiang Mai', 'Khon Kaen', 'Phuket']),
            'postal_code' => fake()->numerify('#####'),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => ['is_default' => true]);
    }
}
