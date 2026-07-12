<x-storefront-layout>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{-- Hero: category sidebar + banner slider --}}
        <div class="grid gap-4 lg:grid-cols-[260px_1fr]">
            <aside class="hidden rounded-2xl border border-gray-100 bg-white p-2 shadow-sm lg:block">
                <p class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-gray-800">
                    <svg class="h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    {{ __('storefront/home.categories_heading') }}
                </p>
                <nav class="mt-1 space-y-0.5">
                    @foreach ($navCategories as $navCategory)
                        <a href="{{ route('categories.show', $navCategory->slug) }}"
                           class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-gray-600 transition hover:bg-primary-50 hover:text-primary-600">
                            {{ $navCategory->name }}
                            <svg class="h-3.5 w-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    @endforeach
                </nav>
            </aside>

            @if ($heroBanners->isNotEmpty())
                <div class="relative overflow-hidden rounded-2xl shadow-sm"
                     x-data="{ current: 0, total: {{ $heroBanners->count() }} }"
                     x-init="setInterval(() => current = (current + 1) % total, 5000)">
                    @foreach ($heroBanners as $banner)
                        <div x-show="current === {{ $loop->index }}"
                             x-transition:enter="transition duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             class="relative aspect-[8/3] w-full" @if(!$loop->first) x-cloak @endif>
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="h-full w-full object-cover" @if(!$loop->first) loading="lazy" @endif>
                            <div class="absolute inset-0 flex flex-col justify-center bg-gradient-to-r from-black/50 via-black/20 to-transparent p-8 sm:p-12">
                                <span class="mb-3 w-fit rounded-full bg-primary-500 px-3 py-1 text-xs font-semibold text-white">{{ __('storefront/home.promo_badge') }}</span>
                                @if ($banner->show_title)
                                    <h2 class="max-w-md text-2xl font-bold leading-tight text-white sm:text-4xl">{{ $banner->title }}</h2>
                                    @if ($banner->subtitle)
                                        <p class="mt-2 max-w-sm text-sm text-gray-100 sm:text-base">{{ $banner->subtitle }}</p>
                                    @endif
                                @else
                                    {{-- Kept for SEO/screen readers even when the image already has its own text baked in. --}}
                                    <span class="sr-only">{{ $banner->title }}</span>
                                @endif
                                <a href="{{ $banner->link ?? route('products.index') }}"
                                   class="mt-5 w-fit rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:bg-primary-600">
                                    {{ __('storefront/home.shop_now') }}
                                </a>
                            </div>
                        </div>
                    @endforeach

                    <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-1.5">
                        @foreach ($heroBanners as $banner)
                            <button @click="current = {{ $loop->index }}" aria-label="{{ __('storefront/home.slide_aria', ['number' => $loop->iteration]) }}"
                                    :class="current === {{ $loop->index }} ? 'w-6 bg-primary-500' : 'w-2 bg-white/70'"
                                    class="h-2 rounded-full transition-all"></button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Feature strip --}}
        <div class="mt-6 grid grid-cols-2 divide-gray-100 rounded-2xl border border-gray-100 bg-white shadow-sm sm:grid-cols-4 sm:divide-x">
            @foreach ([
                ['title' => __('storefront/home.features.free_shipping.title'), 'subtitle' => __('storefront/home.features.free_shipping.subtitle', ['amount' => number_format($freeShippingMin)]), 'icon' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12'],
                ['title' => __('storefront/home.features.free_returns.title'), 'subtitle' => __('storefront/home.features.free_returns.subtitle'), 'icon' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99'],
                ['title' => __('storefront/home.features.secure_payment.title'), 'subtitle' => __('storefront/home.features.secure_payment.subtitle'), 'icon' => 'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z'],
                ['title' => __('storefront/home.features.support.title'), 'subtitle' => __('storefront/home.features.support.subtitle'), 'icon' => 'M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z'],
            ] as $feature)
                <div class="flex items-center gap-3 p-4">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary-50 text-primary-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}"/></svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ $feature['title'] }}</p>
                        <p class="text-xs text-gray-400">{{ $feature['subtitle'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Featured products --}}
        <section class="mt-10" aria-labelledby="featured-heading">
            <div class="flex items-center justify-between">
                <h2 id="featured-heading" class="text-xl font-bold text-gray-900">{{ __('storefront/home.featured_heading') }}</h2>
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">{{ __('storefront/home.view_all') }}</a>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>

        {{-- Promo banners --}}
        @if ($promoBanners->isNotEmpty())
            <section class="mt-10 grid gap-4 sm:grid-cols-2">
                @foreach ($promoBanners as $banner)
                    <a href="{{ $banner->link ?? route('products.index', ['on_sale' => 1]) }}"
                       class="group relative block overflow-hidden rounded-2xl shadow-sm">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" loading="lazy"
                             class="aspect-[2/1] w-full object-cover transition duration-300 group-hover:scale-105">
                        @if ($banner->show_title)
                            <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/60 to-transparent p-6">
                                <h3 class="text-lg font-bold text-white">{{ $banner->title }}</h3>
                                @if ($banner->subtitle)
                                    <p class="mt-1 text-sm text-gray-200">{{ $banner->subtitle }}</p>
                                @endif
                            </div>
                        @else
                            {{-- Kept for SEO/screen readers even when the image already has its own text baked in. --}}
                            <span class="sr-only">{{ $banner->title }}</span>
                        @endif
                    </a>
                @endforeach
            </section>
        @endif

        {{-- Latest products --}}
        <section class="mt-10" aria-labelledby="latest-heading">
            <div class="flex items-center justify-between">
                <h2 id="latest-heading" class="text-xl font-bold text-gray-900">{{ __('storefront/home.latest_heading') }}</h2>
                <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">{{ __('storefront/home.view_all') }}</a>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($latestProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    </div>
</x-storefront-layout>
