<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProductVariantFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    /** @use HasFactory<ProductVariantFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'value',
        'price_modifier',
        'stock',
    ];

    protected function casts(): array
    {
        return [
            'price_modifier' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Final unit price: base product price plus this variant's modifier.
     */
    protected function finalPrice(): Attribute
    {
        return Attribute::get(
            fn (): float => (float) $this->product->price + (float) $this->price_modifier,
        );
    }
}
