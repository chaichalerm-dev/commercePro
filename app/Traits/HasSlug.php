<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Generates a unique slug from the model's name on create.
 * Override slugSource() if the source column is not "name".
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function (Model $model): void {
            if (blank($model->getAttribute('slug'))) {
                $model->setAttribute('slug', $model->generateUniqueSlug());
            }
        });
    }

    protected function slugSource(): string
    {
        return 'name';
    }

    protected function generateUniqueSlug(): string
    {
        $base = Str::slug((string) $this->getAttribute($this->slugSource()));

        if ($base === '') {
            $base = Str::lower(Str::random(8));
        }

        $slug = $base;
        $suffix = 2;

        // withoutGlobalScopes so soft-deleted rows still block their slug.
        while (static::withoutGlobalScopes()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
