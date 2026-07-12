<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    /**
     * Read a setting through the cache; the cache entry lives until the
     * setting is written again, so reads never hit the database twice.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever(
            self::cacheKey($key),
            fn (): ?string => self::query()->where('key', $key)->value('value'),
        ) ?? $default;
    }

    /**
     * Resolve a file-valued setting (logo, favicon) to a browser-usable URL.
     * Returns null when unset, so callers can fall back to a bundled default.
     */
    public static function url(string $key): ?string
    {
        $value = self::get($key);

        if (blank($value)) {
            return null;
        }

        return Str::startsWith($value, ['http://', 'https://']) ? $value : Storage::disk('public')->url($value);
    }

    public static function set(string $key, ?string $value, string $group = 'general'): void
    {
        self::query()->updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);

        Cache::forget(self::cacheKey($key));
    }

    protected static function cacheKey(string $key): string
    {
        return "settings.{$key}";
    }
}
