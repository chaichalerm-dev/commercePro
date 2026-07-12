@php
    $heading = isset($category)
        ? $category->name
        : (filled($filters['q']) ? __('storefront/products.heading.search_results', ['query' => $filters['q']]) : ($filters['on_sale'] ? __('storefront/products.heading.promotions') : __('storefront/products.heading.all_products')));
    $formAction = isset($category) ? route('categories.show', $category->slug) : route('products.index');
@endphp

<x-storefront-layout :title="$heading" :description="isset($category) ? __('storefront/products.description.category', ['category' => $category->name]) : __('storefront/products.description.default')">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <x-breadcrumb :items="isset($category) ? [__('storefront/products.heading.all_products') => route('products.index'), $category->name => null] : [$heading => null]" />

        <div class="mt-4 grid gap-6 lg:grid-cols-[250px_1fr]">
            {{-- Filters --}}
            <aside x-data="{ open: false }" class="lg:sticky lg:top-32 lg:self-start">
                <button @click="open = !open" class="flex w-full items-center justify-between rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium lg:hidden">
                    {{ __('storefront/products.filters.toggle') }}
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>

                <form action="{{ $formAction }}" method="GET" x-show="open || window.innerWidth >= 1024" x-cloak
                      class="mt-3 space-y-5 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm lg:mt-0 lg:!block">
                    @if (filled($filters['q']))
                        <input type="hidden" name="q" value="{{ $filters['q'] }}">
                    @endif
                    <input type="hidden" name="sort" value="{{ $filters['sort'] }}">

                    @unless (isset($category))
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800">{{ __('storefront/products.filters.category') }}</h3>
                            <div class="mt-2 space-y-1.5">
                                @foreach ($navCategories as $navCategory)
                                    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                                        <input type="radio" name="category" value="{{ $navCategory->slug }}"
                                               @checked($filters['category'] === $navCategory->slug)
                                               class="border-gray-300 text-primary-500 focus:ring-primary-400">
                                        {{ $navCategory->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endunless

                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">{{ __('storefront/products.filters.price_range') }}</h3>
                        <div class="mt-2 flex items-center gap-2">
                            <input type="number" name="min_price" value="{{ $filters['min_price'] }}" min="0" placeholder="{{ __('storefront/products.filters.min_price') }}"
                                   class="w-full rounded-lg border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                            <span class="text-gray-400">–</span>
                            <input type="number" name="max_price" value="{{ $filters['max_price'] }}" min="0" placeholder="{{ __('storefront/products.filters.max_price') }}"
                                   class="w-full rounded-lg border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                        </div>
                    </div>

                    <label class="flex cursor-pointer items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="on_sale" value="1" @checked($filters['on_sale'])
                               class="rounded border-gray-300 text-primary-500 focus:ring-primary-400">
                        {{ __('storefront/products.filters.on_sale_only') }}
                    </label>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 rounded-xl bg-primary-500 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">{{ __('storefront/products.filters.apply') }}</button>
                        <a href="{{ $formAction }}" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-500 transition hover:bg-gray-50">{{ __('storefront/products.filters.clear') }}</a>
                    </div>
                </form>
            </aside>

            {{-- Results --}}
            <div>
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $heading }}</h1>
                        <p class="mt-0.5 text-sm text-gray-400">{{ __('storefront/products.results.count', ['count' => number_format($products->total())]) }}</p>
                    </div>
                    <form action="{{ $formAction }}" method="GET" class="flex items-center gap-2">
                        @foreach (['q', 'category', 'min_price', 'max_price'] as $key)
                            @if (filled($filters[$key] ?? null) && ! (isset($category) && $key === 'category'))
                                <input type="hidden" name="{{ $key }}" value="{{ $filters[$key] }}">
                            @endif
                        @endforeach
                        @if ($filters['on_sale'])
                            <input type="hidden" name="on_sale" value="1">
                        @endif
                        <label for="sort" class="text-sm text-gray-500">{{ __('storefront/products.results.sort_label') }}</label>
                        <select id="sort" name="sort" onchange="this.form.submit()"
                                class="rounded-lg border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                            <option value="latest" @selected($filters['sort'] === 'latest')>{{ __('storefront/products.results.sort.latest') }}</option>
                            <option value="popular" @selected($filters['sort'] === 'popular')>{{ __('storefront/products.results.sort.popular') }}</option>
                            <option value="price_asc" @selected($filters['sort'] === 'price_asc')>{{ __('storefront/products.results.sort.price_asc') }}</option>
                            <option value="price_desc" @selected($filters['sort'] === 'price_desc')>{{ __('storefront/products.results.sort.price_desc') }}</option>
                        </select>
                    </form>
                </div>

                @if ($products->isEmpty())
                    <div class="mt-10 flex flex-col items-center rounded-2xl border border-dashed border-gray-200 bg-white py-16 text-center">
                        <svg class="h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="mt-4 font-medium text-gray-700">{{ __('storefront/products.empty.title') }}</p>
                        <p class="mt-1 text-sm text-gray-400">{{ __('storefront/products.empty.subtitle') }}</p>
                        <a href="{{ route('products.index') }}" class="mt-5 rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">{{ __('storefront/products.empty.cta') }}</a>
                    </div>
                @else
                    <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
                        @foreach ($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-storefront-layout>
