<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = User::query()
            ->where('role_id', UserRole::User)
            ->withCount('orders')
            ->withSum(['orders as total_spent' => fn ($query) => $query->where('payment_status', PaymentStatus::Paid)], 'grand_total')
            ->when(filled($request->query('q')), function ($query) use ($request): void {
                $term = trim((string) $request->query('q'));
                $operator = $query->getConnection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
                $query->whereAny(['name', 'email', 'phone'], $operator, "%{$term}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', ['customers' => $customers]);
    }
}
