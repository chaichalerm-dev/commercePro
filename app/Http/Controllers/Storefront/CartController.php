<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\AddToCartRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
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

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $product = Product::query()->findOrFail($request->integer('product_id'));
        $variant = $request->filled('variant_id')
            ? ProductVariant::query()->findOrFail($request->integer('variant_id'))
            : null;

        $this->cart->add($product, $variant, $request->integer('qty'));

        return redirect()->route('cart.index')->with('success', "เพิ่ม \"{$product->name}\" ลงตะกร้าแล้ว");
    }

    public function update(Request $request, int $item): RedirectResponse
    {
        $request->validate(['qty' => ['required', 'integer', 'min:1', 'max:99']]);

        $this->cart->updateQty($this->cart->findItem($item), $request->integer('qty'));

        return back();
    }

    public function destroy(int $item): RedirectResponse
    {
        $this->cart->remove($this->cart->findItem($item));

        return back()->with('success', 'นำสินค้าออกจากตะกร้าแล้ว');
    }
}
