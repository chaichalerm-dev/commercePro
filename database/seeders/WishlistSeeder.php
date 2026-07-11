<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = User::query()->where('email', 'user@example.com')->firstOrFail();

        foreach (Product::query()->inRandomOrder()->take(6)->get() as $product) {
            Wishlist::factory()->for($demoUser)->for($product)->create();
        }
    }
}
