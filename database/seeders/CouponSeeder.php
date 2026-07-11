<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CouponType;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::factory()->create([
            'code' => 'WELCOME10',
            'type' => CouponType::Percent,
            'value' => 10,
            'min_order' => 0,
            'max_uses' => null,
        ]);

        Coupon::factory()->create([
            'code' => 'SAVE100',
            'type' => CouponType::Fixed,
            'value' => 100,
            'min_order' => 1000,
            'max_uses' => 100,
        ]);

        Coupon::factory()->expired()->create(['code' => 'EXPIRED20']);
    }
}
