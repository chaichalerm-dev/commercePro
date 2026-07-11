<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Turns a stored image reference into a browser-usable URL. Accepts either
 * an absolute URL (demo/seed data) or a path on the public storage disk
 * (real uploads), falling back to the bundled placeholder.
 */
trait ResolvesImageUrl
{
    protected function resolveImageUrl(?string $path): string
    {
        if (blank($path)) {
            return asset('images/placeholder.svg');
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::disk('public')->url($path);
    }
}
