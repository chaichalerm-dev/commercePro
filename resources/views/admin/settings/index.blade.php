<x-admin-layout :title="__('admin/settings.title')">
    @php
        $labels = [
            'site_name' => __('admin/settings.fields.site_name'), 'tagline' => __('admin/settings.fields.tagline'),
            'contact_email' => __('admin/settings.fields.contact_email'), 'contact_phone' => __('admin/settings.fields.contact_phone'), 'contact_address' => __('admin/settings.fields.contact_address'),
            'social_facebook' => __('admin/settings.fields.social_facebook'), 'social_instagram' => __('admin/settings.fields.social_instagram'), 'social_line' => __('admin/settings.fields.social_line'), 'social_youtube' => __('admin/settings.fields.social_youtube'),
            'free_shipping_min' => __('admin/settings.fields.free_shipping_min'), 'shipping_fee' => __('admin/settings.fields.shipping_fee'), 'currency' => __('admin/settings.fields.currency'),
        ];
        $groupTitles = [
            'general' => __('admin/settings.groups.general'), 'contact' => __('admin/settings.groups.contact'),
            'social' => __('admin/settings.groups.social'), 'shop' => __('admin/settings.groups.shop'),
        ];
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
            <h2 class="font-semibold text-gray-900">{{ __('admin/settings.logo_section_title') }}</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('admin/settings.fields.logo') }}</label>
                    <input type="file" name="logo" accept="image/*"
                           class="mt-2 w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-600 hover:file:bg-primary-100">
                    <x-input-error :messages="$errors->get('logo')" class="mt-1.5" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ __('admin/settings.fields.favicon') }}</label>
                    <input type="file" name="favicon" accept="image/png,image/x-icon,image/svg+xml"
                           class="mt-2 w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-600 hover:file:bg-primary-100">
                    <x-input-error :messages="$errors->get('favicon')" class="mt-1.5" />
                </div>
            </div>
        </section>

        <button type="submit" class="rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
            {{ __('admin/settings.submit_button') }}
        </button>
    </form>
</x-admin-layout>
