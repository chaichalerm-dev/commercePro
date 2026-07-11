<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\User;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;

class MergeGuestCart
{
    public function __construct(
        private readonly CartService $cart,
    ) {}

    public function handle(Login $event): void
    {
        if ($event->user instanceof User) {
            $this->cart->mergeGuestCart($event->user);
        }
    }
}
