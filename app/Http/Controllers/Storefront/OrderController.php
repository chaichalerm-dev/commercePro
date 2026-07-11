<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orders,
    ) {}

    public function index(Request $request): View
    {
        return view('storefront.orders.index', [
            'orders' => $request->user()
                ->orders()
                ->withCount('items')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(Order $order): View
    {
        $this->authorize('view', $order);

        return view('storefront.orders.show', [
            'order' => $order->load(['items.product', 'address', 'coupon']),
        ]);
    }

    public function cancel(Order $order): RedirectResponse
    {
        $this->authorize('cancel', $order);

        $this->orders->cancel($order);

        return back()->with('success', "ยกเลิกคำสั่งซื้อ {$order->order_number} แล้ว");
    }
}
