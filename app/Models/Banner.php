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
        'show_title',
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
            'show_title' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @param  Builder<Banner>  $query
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * @param  Builder<Banner>  $query
     */
    #[Scope]
    protected function position(Builder $query, BannerPosition $position): void
    {
        $query->where('position', $position)->orderBy('sort_order');
    }

    /**
     * @return Attribute<string, never>
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->resolveImageUrl($this->image));
    }
}
