@props(['product'])

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
    <a href="{{ route('products.show', $product->slug) }}" class="relative block aspect-square overflow-hidden bg-gray-100">
        <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy"
             class="h-full w-full object-cover transition duration-300 group-hover:scale-105">

        <span class="absolute left-3 top-3 flex flex-col gap-1.5">
            @if ($product->discount_percent)
                <span class="rounded-md bg-red-500 px-2 py-0.5 text-xs font-bold text-white shadow">-{{ $product->discount_percent }}%</span>
            @elseif ($product->created_at->gt(now()->subDays(14)))
                <span class="rounded-md bg-emerald-500 px-2 py-0.5 text-xs font-bold text-white shadow">{{ __('storefront/products.badges.new') }}</span>
            @endif
            @unless ($product->isInStock())
                <span class="rounded-md bg-gray-700 px-2 py-0.5 text-xs font-bold text-white shadow">{{ __('storefront/products.badges.out_of_stock') }}</span>
            @endunless
        </span>
    </a>

    <div class="flex flex-1 flex-col p-4">
        <p class="text-xs text-gray-400">{{ $product->category->name }}</p>
        <h3 class="mt-1 line-clamp-2 text-sm font-medium text-gray-800">
            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-primary-600">
                <span class="absolute inset-0" aria-hidden="true"></span>{{ $product->name }}
            </a>
        </h3>

        <div class="mt-auto pt-3">
            <div class="flex items-baseline gap-2">
                <span class="text-lg font-bold text-primary-600">{{ money((float) $product->price) }}</span>
                @if ($product->discount_percent)
                    <span class="text-xs text-gray-400 line-through">{{ money((float) $product->compare_at_price) }}</span>
                @endif
            </div>
            <div class="mt-1.5 flex items-center justify-between">
                <x-star-rating :rating="$product->rating_avg ?? 0" :count="$product->reviews_count ?? 0" />
                <span class="relative flex h-8 w-8 items-center justify-center rounded-lg bg-primary-500 text-white opacity-90 transition group-hover:opacity-100" aria-hidden="true">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                </span>
            </div>
        </div>
    </div>
</article>
