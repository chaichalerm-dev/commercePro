<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_id' => UserRole::User,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('08#-###-####'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'status' => UserStatus::Active,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Full-access admin (Owner tier). Kept named `admin()` for backward
     * compatibility with existing tests that expect unrestricted panel access.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => UserRole::Owner,
        ]);
    }

    public function owner(): static
    {
        return $this->admin();
    }

    /**
     * Mid-tier admin: store operations, but not user/role or settings management.
     */
    public function staffAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => UserRole::Admin,
        ]);
    }

    /**
     * Bottom-tier admin: day-to-day products/orders/reviews only.
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => UserRole::Staff,
        ]);
    }

    public function banned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => UserStatus::Banned,
        ]);
    }
}
