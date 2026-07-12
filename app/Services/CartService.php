<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ProductStatus;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Session key holding the guest cart token. A dedicated token (not the
     * session id) survives the session regeneration that happens at login,
     * which is what makes merging possible.
     */
    private const TOKEN_KEY = 'cart.token';

    /**
     * @return Collection<int, CartItem>
     */
    public function items(): Collection
    {
        return $this->ownerQuery()
            ->with(['product.category', 'variant'])
            ->latest('id')
            ->get();
    }

    public function count(): int
    {
        return (int) $this->ownerQuery()->sum('qty');
    }

    public function subtotal(): float
    {
        return round($this->items()->sum(fn (CartItem $item): float => $item->line_total), 2);
    }

    public function add(Product $product, ?ProductVariant $variant, int $qty): CartItem
    {
        $this->assertPurchasable($product, $variant);

        $item = $this->ownerQuery()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variant?->id)
            ->first();

        $currentQty = $item !== null ? $item->qty : 0;
        $newQty = min($currentQty + $qty, $this->availableStock($product, $variant));

        if ($newQty < 1) {
            throw ValidationException::withMessages(['qty' => __('storefront/cart.errors.out_of_stock')]);
        }

        if ($item !== null) {
            $item->update(['qty' => $newQty]);

            return $item;
        }

        return CartItem::query()->create([
            ...$this->ownerKeys(),
            'product_id' => $product->id,
            'product_variant_id' => $variant?->id,
            'qty' => $newQty,
        ]);
    }

    public function updateQty(CartItem $item, int $qty): CartItem
    {
        $item->loadMissing(['product', 'variant']);

        $item->update(['qty' => max(1, min($qty, $this->availableStock($item->product, $item->variant)))]);

        return $item;
    }

    public function remove(CartItem $item): void
    {
        $item->delete();
    }

    public function clear(): void
    {
        $this->ownerQuery()->delete();
    }

    /**
     * Find a cart item that belongs to the current owner (404 otherwise) —
     * prevents tampering with other users' cart rows.
     */
    public function findItem(int $id): CartItem
    {
        return $this->ownerQuery()->findOrFail($id);
    }

    /**
     * Move guest rows onto the user account after login. Quantities merge
     * (capped by stock) when the user already has the same product line.
     */
    public function mergeGuestCart(User $user): void
    {
        $token = session()->get(self::TOKEN_KEY);

        if (blank($token)) {
            return;
        }

        CartItem::query()
            ->whereNull('user_id')
            ->where('session_id', $token)
            ->with(['product', 'variant'])
            ->get()
            ->each(function (CartItem $guestItem) use ($user): void {
                $existing = CartItem::query()
                    ->where('user_id', $user->id)
                    ->where('product_id', $guestItem->product_id)
                    ->where('product_variant_id', $guestItem->product_variant_id)
                    ->first();

                if ($existing !== null) {
                    $existing->update([
                        'qty' => min($existing->qty + $guestItem->qty, $this->availableStock($guestItem->product, $guestItem->variant)),
                    ]);
                    $guestItem->delete();

                    return;
                }

                $guestItem->update(['user_id' => $user->id, 'session_id' => null]);
            });

        session()->forget(self::TOKEN_KEY);
    }

    /**
     * @return Builder<CartItem>
     */
    protected function ownerQuery(): Builder
    {
        $keys = $this->ownerKeys();

        return CartItem::query()
            ->when(isset($keys['user_id']), fn (Builder $query) => $query->where('user_id', $keys['user_id']))
            ->when(isset($keys['session_id']), fn (Builder $query) => $query->whereNull('user_id')->where('session_id', $keys['session_id']));
    }

    /**
     * @return array{user_id?: int, session_id?: string}
     */
    protected function ownerKeys(): array
    {
        if (auth()->check()) {
            return ['user_id' => (int) auth()->id()];
        }

        $token = session()->get(self::TOKEN_KEY);

        if (blank($token)) {
            $token = (string) Str::uuid();
            session()->put(self::TOKEN_KEY, $token);
        }

        return ['session_id' => $token];
    }

    protected function assertPurchasable(Product $product, ?ProductVariant $variant): void
    {
        if (! $product->isInStock() || $product->status !== ProductStatus::Active) {
            throw ValidationException::withMessages(['qty' => __('storefront/cart.errors.not_purchasable')]);
        }

        if ($variant !== null && $variant->product_id !== $product->id) {
            throw ValidationException::withMessages(['variant_id' => __('storefront/cart.errors.invalid_variant')]);
        }
    }

    protected function availableStock(Product $product, ?ProductVariant $variant): int
    {
        return $variant !== null ? min($variant->stock, $product->stock) : $product->stock;
    }
}
