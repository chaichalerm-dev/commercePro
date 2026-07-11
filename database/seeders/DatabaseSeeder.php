<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            CouponSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            WishlistSeeder::class,
            CartSeeder::class,
            BannerSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
