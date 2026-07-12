@props(['code', 'title', 'message'])

<x-storefront-layout :title="$code.' — '.$title">
    <div class="mx-auto flex max-w-7xl flex-col items-center px-4 py-24 text-center sm:px-6 lg:px-8">
        <p class="text-7xl font-bold text-primary-500">{{ $code }}</p>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ $title }}</h1>
        <p class="mt-2 max-w-md text-sm text-gray-500">{{ $message }}</p>
        <div class="mt-8 flex gap-3">
            <a href="{{ route('home') }}" class="rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">{{ __('errors.back_home') }}</a>
            <a href="{{ route('products.index') }}" class="rounded-xl border border-gray-200 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">{{ __('errors.browse_products') }}</a>
        </div>
    </div>
</x-storefront-layout>
