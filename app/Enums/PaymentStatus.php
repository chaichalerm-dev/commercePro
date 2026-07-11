<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'Unpaid',
            self::Paid => 'Paid',
            self::Refunded => 'Refunded',
        };
    }

    /**
     * Tailwind badge classes for admin/user status chips.
     */
    public function color(): string
    {
        return match ($this) {
            self::Unpaid => 'bg-gray-100 text-gray-600',
            self::Paid => 'bg-emerald-50 text-emerald-600',
            self::Refunded => 'bg-red-50 text-red-500',
        };
    }
}
