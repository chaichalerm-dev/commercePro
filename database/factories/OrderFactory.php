<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Totals default to zero — OrderSeeder (and later OrderService) derives them
 * from the order's items so amounts always add up.
 *
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(OrderStatus::cases());

        return [
            'user_id' => User::factory(),
            'subtotal' => 0,
            'discount' => 0,
            'shipping' => 0,
            'tax' => 0,
            'grand_total' => 0,
            'status' => $status,
            'payment_status' => match ($status) {
                OrderStatus::Cancelled => PaymentStatus::Refunded,
                OrderStatus::Pending => PaymentStatus::Unpaid,
                default => PaymentStatus::Paid,
            },
            'created_at' => fake()->dateTimeBetween('-60 days'),
        ];
    }
}
