<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * The ten storefront categories from the homepage mockup sidebar.
     */
    public function run(): void
    {
        $names = [
            "Women's Fashion",
            "Men's Fashion",
            'Electronics',
            'Mobile & Gadgets',
            'Home & Lifestyle',
            'Beauty & Health',
            'Sports & Outdoor',
            'Mom & Kids',
            'Watches & Accessories',
            'Books & Stationery',
        ];

        foreach ($names as $index => $name) {
            Category::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name),
                'image' => 'https://picsum.photos/seed/'.Str::slug($name).'/400/400',
                'sort_order' => $index,
            ]);
        }
    }
}
