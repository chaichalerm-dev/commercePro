@php
    $contactEmail = \App\Models\Setting::get('contact_email');
    $contactPhone = \App\Models\Setting::get('contact_phone');
    $contactAddress = \App\Models\Setting::get('contact_address');
@endphp

<x-storefront-layout :title="__('storefront/pages.contact.title')" :description="__('storefront/pages.contact.description')">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[__('storefront/pages.contact.title') => null]" />

        <h1 class="mt-6 text-2xl font-bold text-gray-900">{{ __('storefront/pages.contact.heading') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('storefront/pages.contact.subheading') }}</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">{{ __('storefront/pages.contact.phone_label') }}</p>
                <p class="mt-1 text-sm text-primary-600">{{ $contactPhone ?? '-' }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ __('storefront/pages.contact.phone_hours') }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">{{ __('storefront/pages.contact.email_label') }}</p>
                <p class="mt-1 text-sm text-primary-600">{{ $contactEmail ?? '-' }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ __('storefront/pages.contact.email_response') }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <p class="text-sm font-semibold text-gray-800">{{ __('storefront/pages.contact.address_label') }}</p>
                <p class="mt-1 text-sm text-gray-600">{{ $contactAddress ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-bold text-gray-900">{{ __('storefront/pages.contact.faq_heading') }}</h2>
            <div class="mt-4 divide-y divide-gray-100">
                @foreach ([
                    __('storefront/pages.contact.faq.order'),
                    __('storefront/pages.contact.faq.shipping'),
                    __('storefront/pages.contact.faq.returns'),
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
