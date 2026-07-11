<?php

declare(strict_types=1);

namespace App\Enums;

enum BannerPosition: string
{
    case Hero = 'hero';
    case Promo = 'promo';

    public function label(): string
    {
        return match ($this) {
            self::Hero => 'Hero slider',
            self::Promo => 'Promo banner',
        };
    }
}
