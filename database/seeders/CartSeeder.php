<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = User::query()->where('email', 'user@example.com')->firstOrFail();

        foreach (Product::query()->inStock()->inRandomOrder()->take(3)->get() as $product) {
            CartItem::factory()->for($demoUser)->for($product)->create();
        }
    }
}
