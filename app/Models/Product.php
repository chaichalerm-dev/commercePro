<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductStatus;
use App\Traits\HasSlug;
use App\Traits\ResolvesImageUrl;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $category_id
 * @property string $sku
 * @property string $slug
 * @property string $name
 * @property ?string $description
 * @property numeric-string $price
 * @property ?numeric-string $compare_at_price
 * @property int $stock
 * @property ?string $thumbnail
 * @property ProductStatus $status
 * @property bool $featured
 * @property-read string $thumbnail_url
 * @property-read ?int $discount_percent
 */
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasSlug, ResolvesImageUrl, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'slug',
        'name',
        'description',
        'price',
        'compare_at_price',
        'stock',
        'thumbnail',
        'status',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'stock' => 'integer',
            'status' => ProductStatus::class,
            'featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $product): void {
            if (blank($product->sku)) {
                $product->sku = self::generateSku();
            }
        });
    }

    public static function generateSku(): string
    {
        do {
            $sku = 'P'.now()->format('ymd').'-'.strtoupper(Str::random(6));
        } while (self::withoutGlobalScopes()->where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany<ProductImage, $this>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * @return HasMany<ProductVariant, $this>
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return HasMany<Review, $this>
     */
    public function approvedReviews(): HasMany
    {
        return $this->reviews()->where('is_approved', true);
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('status', ProductStatus::Active);
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function featured(Builder $query): void
    {
        $query->where('featured', true);
    }

    /**
     * @param  Builder<Product>  $query
     */
    #[Scope]
    protected function inStock(Builder $query): void
    {
        $query->where('stock', '>', 0);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::get(fn (): string => $this->resolveImageUrl($this->thumbnail));
    }

    /**
     * Discount percentage derived from compare_at_price, e.g. 20 for -20%.
     */
    protected function getDiscountPercentAttribute(): ?int
    {
        $compareAt = (float) $this->compare_at_price;
        $price = (float) $this->price;

        if ($compareAt <= $price) {
            return null;
        }

        return (int) round((1 - $price / $compareAt) * 100);
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
