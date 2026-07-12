<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Demo accounts for the portfolio, one per role tier: all use the password "password".
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@example.com',
        ]);

        User::factory()->staffAdmin()->create([
            'name' => 'Manager Demo',
            'email' => 'manager@example.com',
        ]);

        User::factory()->staff()->create([
            'name' => 'Staff Demo',
            'email' => 'staff@example.com',
        ]);

        User::factory()->create([
            'name' => 'User Demo',
            'email' => 'user@example.com',
            'role_id' => UserRole::User,
        ]);
    }
}
