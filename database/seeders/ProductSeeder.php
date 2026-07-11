<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        Product::factory(50)
            ->recycle($categories)
            ->has(
                ProductImage::factory()
                    ->count(3)
                    ->state(new Sequence(
                        ['sort_order' => 0],
                        ['sort_order' => 1],
                        ['sort_order' => 2],
                    )),
                'images',
            )
            ->afterCreating(function (Product $product): void {
                if (fake()->boolean(50)) {
                    ProductVariant::factory(fake()->numberBetween(2, 4))->for($product)->create();
                }
            })
            ->create();
    }
}
