<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

/**
 * Only the two abilities below are actually checked (both by the storefront
 * OrderController, for a customer viewing/cancelling their own order) — that
 * per-row ownership check is genuine authorization logic a route-level Gate
 * can't express. Admin order management is pure role-based and relies solely
 * on the `can:orders` route Gate (see routes/admin.php) instead of a Policy;
 * don't add an `update`/`viewAny` here that just re-checks `isAdmin()` — see
 * docs/ARCHITECTURE.md's "Policies vs Gates" note for the rule this follows.
 */
class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin() || $order->user_id === $user->id;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->isCancellable();
    }
}
