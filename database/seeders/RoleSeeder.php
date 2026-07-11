<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Ids are fixed so they always match the UserRole enum backing values.
     */
    public function run(): void
    {
        foreach (UserRole::cases() as $role) {
            Role::query()->updateOrCreate(
                ['id' => $role->value],
                ['name' => strtolower($role->name), 'label' => $role->label()],
            );
        }
    }
}
