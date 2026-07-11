<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Cache keys for the homepage fragments — shared by the controller that
 * fills them and the services that must invalidate them on mutation.
 */
final class HomeCache
{
    public const TTL_SECONDS = 600;

    public const HERO_BANNERS = 'home.hero-banners';

    public const PROMO_BANNERS = 'home.promo-banners';

    public const FEATURED = 'home.featured';

    public const LATEST = 'home.latest';

    public static function forget(): void
    {
        foreach ([self::HERO_BANNERS, self::PROMO_BANNERS, self::FEATURED, self::LATEST] as $key) {
            Cache::forget($key);
        }
    }
}
