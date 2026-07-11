<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CartItemFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property ?int $user_id
 * @property ?string $session_id
 * @property int $product_id
 * @property ?int $product_variant_id
 * @property int $qty
 * @property-read float $unit_price
 * @property-read float $line_total
 */
class CartItem extends Model
{
    /** @use HasFactory<CartItemFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'product_variant_id',
        'qty',
    ];

    protected function casts(): array
    {
        return [
            'qty' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Product, $this>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo<ProductVariant, $this>
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * @return Attribute<float, never>
     */
    protected function unitPrice(): Attribute
    {
        return Attribute::get(function (): float {
            $base = (float) $this->product->price;

            return $this->variant !== null ? $base + (float) $this->variant->price_modifier : $base;
        });
    }

    /**
     * @return Attribute<float, never>
     */
    protected function lineTotal(): Attribute
    {
        return Attribute::get(fn (): float => round($this->unit_price * $this->qty, 2));
    }
}
