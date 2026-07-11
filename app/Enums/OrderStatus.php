<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Processing => 'Processing',
            self::Shipped => 'Shipped',
            self::Delivered => 'Delivered',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * Tailwind badge classes for admin/user status chips.
     */
    public function color(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-50 text-amber-600',
            self::Processing => 'bg-blue-50 text-blue-600',
            self::Shipped => 'bg-violet-50 text-violet-600',
            self::Delivered => 'bg-emerald-50 text-emerald-600',
            self::Cancelled => 'bg-red-50 text-red-500',
        };
    }

    /**
     * Statuses an order can move to from the current one.
     *
     * @return list<self>
     */
    public function transitions(): array
    {
        return match ($this) {
            self::Pending => [self::Processing, self::Cancelled],
            self::Processing => [self::Shipped, self::Cancelled],
            self::Shipped => [self::Delivered],
            self::Delivered, self::Cancelled => [],
        };
    }
}
