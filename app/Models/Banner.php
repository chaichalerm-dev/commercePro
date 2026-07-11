<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BannerPosition;
use App\Traits\ResolvesImageUrl;
use Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    /** @use HasFactory<BannerFactory> */
    use HasFactory, ResolvesImageUrl;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'link',
        'position',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'position' => BannerPosition::class,
            'is_active' => 'boolean',
        ];
    }

    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function position(Builder $query, BannerPosition $position): void
    {
        $query->where('position', $position)->orderBy('sort_order');
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->resolveImageUrl($this->image));
    }
}
