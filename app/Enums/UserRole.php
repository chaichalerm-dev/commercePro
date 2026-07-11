<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Mirrors the seeded rows of the `roles` table — keep ids in sync with RoleSeeder.
 */
enum UserRole: int
{
    case Admin = 1;
    case User = 2;

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::User => 'Customer',
        };
    }
}
