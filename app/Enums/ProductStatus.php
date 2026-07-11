<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStatus: string
{
    case Active = 'active';
    case Draft = 'draft';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Draft => 'Draft',
            self::Archived => 'Archived',
        };
    }

    /**
     * Tailwind badge classes for admin status chips.
     */
    public function color(): string
    {
        return match ($this) {
            self::Active => 'bg-emerald-50 text-emerald-600',
            self::Draft => 'bg-amber-50 text-amber-600',
            self::Archived => 'bg-gray-100 text-gray-500',
        };
    }
}
