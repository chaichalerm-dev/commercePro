<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Generates a unique slug from the model's name when the slug is blank —
 * on create and on update. Override slugSource() if the source column is
 * not "name".
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        $fill = function (self $model): void {
            if (blank($model->getAttribute('slug'))) {
                $model->setAttribute('slug', $model->generateUniqueSlug());
            }
        };

        static::creating($fill);
        static::updating($fill);
    }

    protected function slugSource(): string
    {
        return 'name';
    }

    public function generateUniqueSlug(): string
    {
        $base = Str::slug((string) $this->getAttribute($this->slugSource()));

        if ($base === '') {
            $base = Str::lower(Str::random(8));
        }

        $slug = $base;
        $suffix = 2;

        // withoutGlobalScopes so soft-deleted rows still block their slug;
        // the model's own row never blocks itself (relevant on update).
        while (static::withoutGlobalScopes()
            ->where('slug', $slug)
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))
            ->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
