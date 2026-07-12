<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\AddToCartRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
    ) {}

    public function index(): View
    {
        return view('storefront.cart.index', [
            'items' => $this->cart->items(),
            'subtotal' => $this->cart->subtotal(),
        ]);
    }

    /**
     * JSON snapshot of the cart for the header slide-over drawer.
     */
    public function mini(): JsonResponse
    {
        return response()->json($this->payload());
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $product = Product::query()->findOrFail($request->integer('product_id'));
        $variant = $request->filled('variant_id')
            ? ProductVariant::query()->findOrFail($request->integer('variant_id'))
            : null;

        $this->cart->add($product, $variant, $request->integer('qty'));

        return redirect()->route('cart.index')->with('success', __('storefront/cart.flash.added', ['product' => $product->name]));
    }

    public function update(Request $request, int $item): RedirectResponse|JsonResponse
    {
        $request->validate(['qty' => ['required', 'integer', 'min:1', 'max:99']]);

        $this->cart->updateQty($this->cart->findItem($item), $request->integer('qty'));

        return $request->wantsJson() ? response()->json($this->payload()) : back();
    }

    public function destroy(Request $request, int $item): RedirectResponse|JsonResponse
    {
        $this->cart->remove($this->cart->findItem($item));

        if ($request->wantsJson()) {
            return response()->json($this->payload());
        }

        return back()->with('success', __('storefront/cart.flash.removed'));
    }

    /**
     * @return array{count: int, subtotal: string, items: array<int, array<string, mixed>>}
     */
    private function payload(): array
    {
        $items = $this->cart->items();

        return [
            'count' => (int) $items->sum('qty'),
            'subtotal' => money($this->cart->subtotal()),
            'items' => $items->map(fn (CartItem $item): array => [
                'id' => $item->id,
                'name' => $item->product->name,
                'url' => route('products.show', $item->product->slug),
                'thumbnail' => $item->product->thumbnail_url,
                'variant' => $item->variant !== null ? "{$item->variant->name}: {$item->variant->value}" : null,
                'qty' => $item->qty,
                'maxQty' => $this->cart->availableStock($item->product, $item->variant),
                'lineTotal' => money($item->line_total),
            ])->values()->all(),
        ];
    }
}
