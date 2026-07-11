<x-admin-layout title="ตั้งค่าเว็บไซต์">
    @php
        $labels = [
            'site_name' => 'ชื่อเว็บไซต์', 'tagline' => 'คำโปรย',
            'contact_email' => 'อีเมลติดต่อ', 'contact_phone' => 'เบอร์โทรศัพท์', 'contact_address' => 'ที่อยู่',
            'social_facebook' => 'Facebook URL', 'social_instagram' => 'Instagram URL', 'social_line' => 'LINE URL', 'social_youtube' => 'YouTube URL',
            'free_shipping_min' => 'ยอดขั้นต่ำส่งฟรี (บาท)', 'shipping_fee' => 'ค่าจัดส่ง (บาท)', 'currency' => 'สกุลเงิน',
        ];
        $groupTitles = ['general' => 'ทั่วไป', 'contact' => 'ข้อมูลติดต่อ', 'social' => 'โซเชียลมีเดีย', 'shop' => 'การขายและจัดส่ง'];
    @endphp

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="max-w-3xl space-y-4">
        @csrf @method('PATCH')

        @foreach ($groups as $group => $keys)
            <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-gray-900">{{ $groupTitles[$group] ?? $group }}</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach ($keys as $key)
                        <div @class(['sm:col-span-2' => in_array($key, ['tagline', 'contact_address'])])>
                            <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">{{ $labels[$key] ?? $key }}</label>
                            <input id="{{ $key }}" type="text" name="{{ $key }}"
                                   value="{{ old($key, $settings[$key]->value ?? '') }}"
                                   class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                            <x-input-error :messages="$errors->get($key)" class="mt-1.5" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-gray-900">โลโก้และไอคอน</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">โลโก้</label>
                    <input type="file" name="logo" accept="image/*"
                           class="mt-2 w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-600 hover:file:bg-primary-100">
                    <x-input-error :messages="$errors->get('logo')" class="mt-1.5" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Favicon</label>
                    <input type="file" name="favicon" accept="image/png,image/x-icon,image/svg+xml"
                           class="mt-2 w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-600 hover:file:bg-primary-100">
                    <x-input-error :messages="$errors->get('favicon')" class="mt-1.5" />
                </div>
            </div>
        </section>

        <button type="submit" class="rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
            บันทึกการตั้งค่า
        </button>
    </form>
</x-admin-layout>
