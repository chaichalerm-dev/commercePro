<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $service,
    ) {}

    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with('user')
            ->withCount('items')
            ->when(filled($request->query('q')), function ($query) use ($request): void {
                $term = trim((string) $request->query('q'));
                $operator = DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $query->where(fn ($sub) => $sub
                    ->where('order_number', $operator, "%{$term}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', $operator, "%{$term}%")));
            })
            ->when(filled($request->query('status')), fn ($query) => $query->where('status', $request->query('status')))
            ->when(filled($request->query('payment')), fn ($query) => $query->where('payment_status', $request->query('payment')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
            'paymentStatuses' => PaymentStatus::cases(),
        ]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', [
            'order' => $order->load(['items.product', 'user', 'address', 'coupon']),
            'paymentStatuses' => PaymentStatus::cases(),
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate(['status' => ['required', Rule::enum(OrderStatus::class)]]);

        $this->service->transition($order, OrderStatus::from($validated['status']));

        return back()->with('success', __('admin/orders.flash.status_updated', ['status' => $order->refresh()->status->label()]));
    }

    public function updatePayment(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate(['payment_status' => ['required', Rule::enum(PaymentStatus::class)]]);

        $this->service->updatePaymentStatus($order, PaymentStatus::from($validated['payment_status']));

        return back()->with('success', __('admin/orders.flash.payment_updated'));
    }
}
