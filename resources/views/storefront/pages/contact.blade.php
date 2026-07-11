@php
    $contactEmail = \App\Models\Setting::get('contact_email');
    $contactPhone = \App\Models\Setting::get('contact_phone');
    $contactAddress = \App\Models\Setting::get('contact_address');
@endphp

<x-storefront-layout title="ติดต่อเรา" description="ช่องทางติดต่อทีมงาน ShopSmart พร้อมให้บริการทุกวัน">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <x-breadcrumb :items="['ติดต่อเรา' => null]" />

        <h1 class="mt-6 text-2xl font-bold text-gray-900">ติดต่อเรา</h1>
        <p class="mt-1 text-sm text-gray-500">ทีมงานพร้อมให้บริการและตอบทุกคำถามของคุณ</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">โทรศัพท์</p>
                <p class="mt-1 text-sm text-primary-600">{{ $contactPhone ?? '-' }}</p>
                <p class="mt-1 text-xs text-gray-400">ทุกวัน 09:00 – 21:00 น.</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">อีเมล</p>
                <p class="mt-1 text-sm text-primary-600">{{ $contactEmail ?? '-' }}</p>
                <p class="mt-1 text-xs text-gray-400">ตอบกลับภายใน 24 ชั่วโมง</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">ที่อยู่</p>
                <p class="mt-1 text-sm text-gray-600">{{ $contactAddress ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-bold text-gray-900">คำถามที่พบบ่อย</h2>
            <div class="mt-4 divide-y divide-gray-100">
                @foreach ([
                    ['q' => 'สั่งซื้อสินค้าอย่างไร?', 'a' => 'เลือกสินค้าที่ต้องการ เพิ่มลงตะกร้า จากนั้นทำตามขั้นตอนการชำระเงิน (ระบบเดโม ไม่มีการตัดเงินจริง)'],
                    ['q' => 'จัดส่งใช้เวลากี่วัน?', 'a' => 'โดยปกติ 1-3 วันทำการสำหรับกรุงเทพฯ และ 3-5 วันทำการสำหรับต่างจังหวัด'],
                    ['q' => 'คืนสินค้าได้ไหม?', 'a' => 'คืนสินค้าได้ฟรีภายใน 7 วันหลังได้รับสินค้า หากสินค้ามีปัญหาหรือไม่ตรงตามที่สั่ง'],
                ] as $faq)
                    <details class="group py-3">
                        <summary class="flex cursor-pointer items-center justify-between text-sm font-medium text-gray-800 marker:content-none">
                            {{ $faq['q'] }}
                            <svg class="h-4 w-4 text-gray-400 transition group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                        </summary>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</x-storefront-layout>
