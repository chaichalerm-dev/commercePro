<x-storefront-layout :title="__('storefront/pages.about.title')" :description="__('storefront/pages.about.description', ['site' => $siteName])">
    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[__('storefront/pages.about.title') => null]" />

        <div class="mt-6 overflow-hidden rounded-2xl bg-gradient-to-r from-primary-500 to-amber-400 p-8 text-white sm:p-12">
            <h1 class="text-3xl font-bold">{{ __('storefront/pages.about.heading', ['site' => $siteName]) }}</h1>
            <p class="mt-3 max-w-xl leading-relaxed text-primary-50">{{ $siteTagline }}</p>
        </div>

        <div class="mt-8 grid gap-6 sm:grid-cols-3">
            @foreach ([
                __('storefront/pages.about.stats.products'),
                __('storefront/pages.about.stats.categories'),
                __('storefront/pages.about.stats.support'),
            ] as $stat)
                <div class="rounded-2xl border border-gray-100 bg-white p-6 text-center shadow-sm">
                    <p class="text-3xl font-bold text-primary-600">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="prose prose-sm mt-8 max-w-none rounded-2xl border border-gray-100 bg-white p-6 text-gray-600 shadow-sm sm:p-8">
            <p>{{ __('storefront/pages.about.paragraph1', ['site' => $siteName]) }}</p>
            <p>{{ __('storefront/pages.about.paragraph2') }}</p>
        </div>
    </div>
</x-storefront-layout>
