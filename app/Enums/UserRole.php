<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Mirrors the seeded rows of the `roles` table — keep ids in sync with RoleSeeder.
 *
 * Ids are not sequential by rank: `role_id = 1` predates the tiered admin system
 * and always meant "full access", so it stays mapped to Owner rather than being
 * renumbered (renumbering would silently reinterpret existing rows). `User = 2`
 * must never change either, for the same reason.
 */
enum UserRole: int
{
    case Owner = 1;
    case User = 2;
    case Admin = 3;
    case Staff = 4;

    public function label(): string
    {
        return __('enums.user_role.'.strtolower($this->name));
    }

    /**
     * Relative rank among admin tiers; higher can manage lower. Customers sit at 0.
     */
    public function level(): int
    {
        return match ($this) {
            self::Owner => 3,
            self::Admin => 2,
            self::Staff => 1,
            self::User => 0,
        };
    }

    /**
     * Whether this role has any admin-panel access at all.
     */
    public function isAdminTier(): bool
    {
        return $this !== self::User;
    }

    /**
     * Staff/admin tiers only — excludes the customer-facing User role.
     *
     * @return array<int, self>
     */
    public static function adminTiers(): array
    {
        return array_values(array_filter(self::cases(), fn (self $role) => $role->isAdminTier()));
    }

    /**
     * Whether this role may use the given admin-panel ability. Owner can do
     * everything; Admin runs store operations but not user/role or system
     * settings management; Staff is restricted to day-to-day catalog work.
     */
    public function can(string $ability): bool
    {
        if ($this === self::Owner) {
            return true;
        }

        return match ($this) {
            self::Admin => in_array($ability, [
                'products', 'orders', 'reviews', 'categories', 'coupons', 'banners', 'customers', 'logs',
            ], true),
            self::Staff => in_array($ability, ['products', 'orders', 'reviews'], true),
            self::User => false,
        };
    }
}
