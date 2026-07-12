<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'general' => [
                'site_name' => 'ShopSmart',
                'tagline' => 'ร้านค้าออนไลน์ที่รวมสินค้าคุณภาพ ในราคาที่ดีที่สุดสำหรับคุณ',
                'logo' => null,
                'favicon' => null,
            ],
            'contact' => [
                'contact_email' => 'support@shopsmart.test',
                'contact_phone' => '02-000-0000',
                'contact_address' => '99 Rama IX Rd, Bangkok 10310',
            ],
            'social' => [
                'social_facebook' => 'https://facebook.com/shopsmart',
                'social_instagram' => 'https://instagram.com/shopsmart',
                'social_line' => 'https://line.me/@shopsmart',
                'social_youtube' => 'https://youtube.com/@shopsmart',
            ],
            'shop' => [
                'free_shipping_min' => '1000',
                'shipping_fee' => '50',
                'currency' => 'THB',
            ],
            'security' => [
                'show_demo_credentials' => '1',
            ],
        ];

        foreach ($settings as $group => $pairs) {
            foreach ($pairs as $key => $value) {
                Setting::set($key, $value, $group);
            }
        }
    }
}
