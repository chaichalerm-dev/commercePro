<x-storefront-layout title="เกี่ยวกับเรา" description="รู้จัก ShopSmart ร้านค้าออนไลน์ที่คัดสรรสินค้าคุณภาพในราคาที่ดีที่สุด">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <x-breadcrumb :items="['เกี่ยวกับเรา' => null]" />

        <div class="mt-6 overflow-hidden rounded-2xl bg-gradient-to-r from-primary-500 to-amber-400 p-8 text-white sm:p-12">
            <h1 class="text-3xl font-bold">เกี่ยวกับ {{ $siteName }}</h1>
            <p class="mt-3 max-w-xl leading-relaxed text-primary-50">{{ $siteTagline }}</p>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-3">
            @foreach ([
                ['value' => '50+', 'label' => 'สินค้าคุณภาพคัดสรร'],
                ['value' => '10', 'label' => 'หมวดหมู่ครบครัน'],
                ['value' => '24 ชม.', 'label' => 'บริการลูกค้า'],
            ] as $stat)
                <div class="rounded-2xl border border-gray-100 bg-white p-6 text-center shadow-sm">
                    <p class="text-3xl font-bold text-primary-600">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="prose prose-sm mt-8 max-w-none rounded-2xl border border-gray-100 bg-white p-6 text-gray-600 shadow-sm sm:p-8">
            <p>{{ $siteName }} ก่อตั้งขึ้นด้วยความตั้งใจที่จะทำให้การช้อปปิ้งออนไลน์เป็นเรื่องง่าย ปลอดภัย และคุ้มค่า เราคัดสรรสินค้าคุณภาพจากหลากหลายหมวดหมู่ ตั้งแต่แฟชั่น อิเล็กทรอนิกส์ ไปจนถึงของใช้ในบ้าน พร้อมบริการจัดส่งที่รวดเร็วและระบบชำระเงินที่ปลอดภัย</p>
            <p>โปรเจคนี้เป็นเดโมสำหรับ Portfolio — พัฒนาด้วย Laravel 12, Tailwind CSS, Alpine.js และ PostgreSQL (Supabase) ตามแนวทาง Clean Architecture ที่พร้อมต่อยอดสู่ระบบ Production จริง</p>
        </div>
    </div>
</x-storefront-layout>
