<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->where('role_id', UserRole::User)->get();
        $products = Product::all();
        $used = [];

        while (count($used) < 10) {
            $user = $users->random();
            $product = $products->random();
            $pair = "{$user->id}-{$product->id}";

            if (isset($used[$pair])) {
                continue;
            }

            $used[$pair] = true;

            Review::factory()->for($user)->for($product)->create();
        }
    }
}
