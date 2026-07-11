<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\ResolvesImageUrl;
use Database\Factories\ProductImageFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    /** @use HasFactory<ProductImageFactory> */
    use HasFactory, ResolvesImageUrl;

    protected $fillable = [
        'product_id',
        'path',
        'sort_order',
    ];

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => $this->resolveImageUrl($this->path));
    }
}
