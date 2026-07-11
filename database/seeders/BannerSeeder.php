<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $heroes = [
            ['title' => 'ช้อปของดี ดีลสุดคุ้ม', 'subtitle' => 'รวมสินค้าคุณภาพ ราคาพิเศษ ส่งตรงถึงมือคุณ'],
            ['title' => 'สินค้าใหม่ประจำสัปดาห์', 'subtitle' => 'อัปเดตเทรนด์ก่อนใคร พร้อมส่วนลดพิเศษ'],
            ['title' => 'ส่งฟรีเมื่อช้อปครบ 1,000 บาท', 'subtitle' => 'ทุกคำสั่งซื้อทั่วประเทศ ไม่มีขั้นต่ำแอบแฝง'],
        ];

        foreach ($heroes as $index => $hero) {
            Banner::factory()->create([
                ...$hero,
                'image' => 'https://picsum.photos/seed/hero-'.($index + 1).'/1600/600',
                'sort_order' => $index,
            ]);
        }

        Banner::factory()->promo()->create([
            'title' => 'โปรโมชั่นพิเศษ',
            'subtitle' => 'ลดสูงสุด 50% เฉพาะสินค้าที่ร่วมรายการ',
            'image' => 'https://picsum.photos/seed/promo-1/800/400',
        ]);

        Banner::factory()->promo()->create([
            'title' => 'สมาชิกใหม่รับส่วนลด 10%',
            'subtitle' => 'ใช้โค้ด WELCOME10 เมื่อสั่งซื้อครั้งแรก',
            'image' => 'https://picsum.photos/seed/promo-2/800/400',
            'sort_order' => 1,
        ]);
    }
}
