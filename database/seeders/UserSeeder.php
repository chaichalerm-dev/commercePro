<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Demo accounts for the portfolio: both use the password "password".
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'name' => 'User Demo',
            'email' => 'user@example.com',
            'role_id' => UserRole::User,
        ]);
    }
}
