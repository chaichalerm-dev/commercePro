<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private const LOW_STOCK_THRESHOLD = 10;

    /**
     * Headline numbers for the stat cards.
     *
     * @return array{revenue: float, orders: int, pending_orders: int, products: int, customers: int}
     */
    public function cards(): array
    {
        return [
            'revenue' => (float) Order::query()->where('payment_status', PaymentStatus::Paid)->sum('grand_total'),
            'orders' => Order::query()->count(),
            'pending_orders' => Order::query()->where('status', OrderStatus::Pending)->count(),
            'products' => Product::query()->count(),
            'customers' => User::query()->where('role_id', UserRole::User)->count(),
        ];
    }

    /**
     * Daily paid revenue for the last N days. Grouped in PHP so the query
     * stays driver-agnostic (pgsql in production, sqlite in tests).
     *
     * @return array{labels: list<string>, values: list<float>}
     */
    public function revenueChart(int $days = 30): array
    {
        $from = now()->subDays($days - 1)->startOfDay();

        $byDay = Order::query()
            ->where('payment_status', PaymentStatus::Paid)
            ->where('created_at', '>=', $from)
            ->get(['created_at', 'grand_total'])
            ->groupBy(fn (Order $order): string => $order->created_at->format('Y-m-d'))
            ->map(fn ($orders): float => (float) $orders->sum('grand_total'));

        $labels = [];
        $values = [];

        foreach (range(0, $days - 1) as $offset) {
            $date = $from->copy()->addDays($offset);
            $labels[] = $date->format('d M');
            $values[] = round($byDay->get($date->format('Y-m-d'), 0.0), 2);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Order counts per status for the doughnut chart.
     *
     * @return array{labels: list<string>, values: list<int>}
     */
    public function ordersByStatus(): array
    {
        $counts = Order::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [];
        $values = [];

        foreach (OrderStatus::cases() as $status) {
            $labels[] = $status->label();
            $values[] = (int) ($counts[$status->value] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * @return Collection<int, Order>
     */
    public function recentOrders(int $limit = 8): Collection
    {
        return Order::query()->with('user')->latest()->limit($limit)->get();
    }

    /**
     * Best sellers by quantity across paid orders.
     *
     * @return BaseCollection<int, object{product_id: ?int, product_name: string, qty_sold: int, revenue: float}>
     */
    public function topProducts(int $limit = 5): BaseCollection
    {
        return OrderItem::query()
            ->select('product_id', 'product_name')
            ->selectRaw('sum(qty) as qty_sold, sum(total) as revenue')
            ->whereHas('order', fn ($query) => $query->where('payment_status', PaymentStatus::Paid))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('qty_sold')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, Product>
     */
    public function lowStockProducts(int $limit = 5): Collection
    {
        return Product::query()
            ->active()
            ->where('stock', '<', self::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, User>
     */
    public function latestCustomers(int $limit = 5): Collection
    {
        return User::query()
            ->where('role_id', UserRole::User)
            ->latest()
            ->limit($limit)
            ->get();
    }
}
