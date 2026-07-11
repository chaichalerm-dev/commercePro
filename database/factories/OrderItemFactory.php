<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 3);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => fn (array $attributes) => Product::find($attributes['product_id'])->name,
            'qty' => $qty,
            'price' => fn (array $attributes) => Product::find($attributes['product_id'])->price,
            'total' => fn (array $attributes) => round((float) $attributes['price'] * $qty, 2),
        ];
    }
}
