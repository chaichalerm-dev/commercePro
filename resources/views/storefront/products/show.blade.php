<x-storefront-layout :title="$product->name"
                     :description="str($product->description)->limit(150)->toString()"
                     :image="$product->thumbnail_url">
    <x-slot name="head">
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'sku' => $product->sku,
            'image' => $product->thumbnail_url,
            'description' => str($product->description)->limit(300)->toString(),
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'THB',
                'price' => (float) $product->price,
                'availability' => $product->isInStock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => route('products.show', $product->slug),
            ],
            ...($product->reviews_count > 0 ? ['aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => round((float) $product->rating_avg, 1),
                'reviewCount' => $product->reviews_count,
            ]] : []),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[
            'สินค้าทั้งหมด' => route('products.index'),
            $product->category->name => route('categories.show', $product->category->slug),
            $product->name => null,
        ]" />

        <div class="mt-5 grid gap-8 lg:grid-cols-2">
            {{-- Gallery --}}
            @php $gallery = $product->images->isNotEmpty() ? $product->images->map->url : collect([$product->thumbnail_url]); @endphp
            <div x-data="{ active: 0 }">
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    @foreach ($gallery as $url)
                        <img x-show="active === {{ $loop->index }}" src="{{ $url }}" alt="{{ $product->name }}"
                             class="aspect-square w-full object-cover" @if(!$loop->first) x-cloak loading="lazy" @endif>
                    @endforeach
                </div>
                @if ($gallery->count() > 1)
                    <div class="mt-3 flex gap-3">
                        @foreach ($gallery as $url)
                            <button @click="active = {{ $loop->index }}"
                                    :class="active === {{ $loop->index }} ? 'ring-2 ring-primary-500' : 'ring-1 ring-gray-200 opacity-70 hover:opacity-100'"
                                    class="overflow-hidden rounded-xl transition">
                                <img src="{{ $url }}" alt="" loading="lazy" class="h-16 w-16 object-cover sm:h-20 sm:w-20">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div>
                <p class="text-sm text-primary-600">
                    <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:underline">{{ $product->category->name }}</a>
                </p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">{{ $product->name }}</h1>

                <div class="mt-2 flex items-center gap-3 text-sm">
                    <x-star-rating :rating="$product->rating_avg ?? 0" />
                    <span class="text-gray-500">{{ number_format((float) ($product->rating_avg ?? 0), 1) }} ({{ $product->reviews_count }} รีวิว)</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-400">SKU: {{ $product->sku }}</span>
                </div>

                <div class="mt-5 flex items-baseline gap-3 rounded-2xl bg-gray-50 p-4">
                    <span class="text-3xl font-bold text-primary-600">{{ money((float) $product->price) }}</span>
                    @if ($product->discount_percent)
                        <span class="text-lg text-gray-400 line-through">{{ money((float) $product->compare_at_price) }}</span>
                        <span class="rounded-md bg-red-500 px-2 py-0.5 text-xs font-bold text-white">-{{ $product->discount_percent }}%</span>
                    @endif
                </div>

                <p class="mt-4 flex items-center gap-2 text-sm {{ $product->isInStock() ? 'text-emerald-600' : 'text-red-500' }}">
                    <span class="h-2 w-2 rounded-full {{ $product->isInStock() ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                    {{ $product->isInStock() ? 'มีสินค้า ('.$product->stock.' ชิ้น)' : 'สินค้าหมด' }}
                </p>

                <div class="mt-6 flex flex-wrap items-start gap-3">
                    <form method="POST" action="{{ route('cart.store') }}"
                          x-data="{ qty: 1, max: {{ max($product->stock, 1) }}, variantId: null }"
                          class="flex flex-wrap items-end gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="variant_id" :value="variantId ?? ''">
                        <input type="hidden" name="qty" :value="qty">

                        @foreach ($product->variants->groupBy('name') as $groupName => $variants)
                            <div class="w-full">
                                <h3 class="text-sm font-semibold text-gray-800">{{ $groupName }}</h3>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($variants as $variant)
                                        <button type="button" @click="variantId = variantId === {{ $variant->id }} ? null : {{ $variant->id }}"
                                                :class="variantId === {{ $variant->id }} ? 'border-primary-500 bg-primary-50 text-primary-600' : 'border-gray-200 text-gray-600 hover:border-gray-300'"
                                                class="rounded-xl border px-4 py-2 text-sm font-medium transition {{ $variant->stock === 0 ? 'opacity-40' : '' }}"
                                                @disabled($variant->stock === 0)>
                                            {{ $variant->value }}
                                            @if ((float) $variant->price_modifier > 0)
                                                <span class="text-xs text-gray-400">+{{ money((float) $variant->price_modifier) }}</span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="flex items-center rounded-xl border border-gray-200">
                            <button type="button" @click="qty = Math.max(1, qty - 1)" class="px-4 py-2.5 text-gray-500 hover:text-primary-600">−</button>
                            <span class="w-10 text-center text-sm font-semibold" x-text="qty"></span>
                            <button type="button" @click="qty = Math.min(max, qty + 1)" class="px-4 py-2.5 text-gray-500 hover:text-primary-600">+</button>
                        </div>

                        <button type="submit"
                                class="flex items-center gap-2 rounded-xl bg-primary-500 px-8 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600 disabled:opacity-50"
                                @disabled(! $product->isInStock())>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                            เพิ่มลงตะกร้า
                        </button>
                    </form>

                    <form method="POST" action="{{ route('wishlist.toggle') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" title="{{ auth()->check() ? 'เพิ่ม/นำออกจากรายการโปรด' : 'เข้าสู่ระบบเพื่อใช้รายการโปรด' }}"
                                class="rounded-xl border p-2.5 transition {{ auth()->user()?->wishlists()->where('product_id', $product->id)->exists() ? 'border-red-200 bg-red-50 text-red-500' : 'border-gray-200 text-gray-400 hover:border-red-200 hover:text-red-500' }}"
                                aria-label="รายการโปรด">
                            <svg class="h-5 w-5" fill="{{ auth()->user()?->wishlists()->where('product_id', $product->id)->exists() ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                        </button>
                    </form>
                </div>
                <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                <x-input-error :messages="$errors->get('variant_id')" class="mt-2" />
            </div>
        </div>

        {{-- Description --}}
        <section class="mt-12 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-bold text-gray-900">รายละเอียดสินค้า</h2>
            <div class="prose prose-sm mt-4 max-w-none whitespace-pre-line text-gray-600">{{ $product->description }}</div>
        </section>

        {{-- Reviews --}}
        <section class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-lg font-bold text-gray-900">รีวิวจากผู้ซื้อ ({{ $product->reviews_count }})</h2>
            @forelse ($product->reviews as $review)
                <article class="mt-5 border-b border-gray-100 pb-5 last:border-0 last:pb-0">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">
                            {{ mb_substr($review->user->name, 0, 1) }}
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $review->user->name }}</p>
                            <div class="flex items-center gap-2">
                                <x-star-rating :rating="$review->rating" />
                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @if ($review->comment)
                        <p class="mt-2.5 text-sm leading-relaxed text-gray-600">{{ $review->comment }}</p>
                    @endif
                </article>
            @empty
                <p class="mt-4 text-sm text-gray-400">ยังไม่มีรีวิวสำหรับสินค้านี้</p>
            @endforelse
        </section>

        {{-- Related --}}
        @if ($relatedProducts->isNotEmpty())
            <section class="mt-10">
                <h2 class="text-xl font-bold text-gray-900">สินค้าที่คุณอาจสนใจ</h2>
                <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @foreach ($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-storefront-layout>
