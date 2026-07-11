<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {}

    public function index(): View
    {
        return view('admin.dashboard', [
            'cards' => $this->dashboard->cards(),
            'revenueChart' => $this->dashboard->revenueChart(),
            'ordersByStatus' => $this->dashboard->ordersByStatus(),
            'recentOrders' => $this->dashboard->recentOrders(),
            'topProducts' => $this->dashboard->topProducts(),
            'lowStockProducts' => $this->dashboard->lowStockProducts(),
            'latestCustomers' => $this->dashboard->latestCustomers(),
        ]);
    }
}
