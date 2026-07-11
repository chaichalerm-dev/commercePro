<x-storefront-layout title="รายการโปรด">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <x-breadcrumb :items="['รายการโปรด' => null]" />
        <h1 class="mt-4 text-2xl font-bold text-gray-900">รายการโปรดของฉัน</h1>

        @if ($wishlists->isEmpty())
            <div class="mt-8 flex flex-col items-center rounded-2xl border border-dashed border-gray-200 bg-white py-16 text-center">
                <svg class="h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                <p class="mt-4 font-medium text-gray-700">ยังไม่มีสินค้าในรายการโปรด</p>
                <a href="{{ route('products.index') }}" class="mt-5 rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">เลือกซื้อสินค้า</a>
            </div>
        @else
            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($wishlists as $wishlist)
                    <div class="relative">
                        <x-product-card :product="$wishlist->product" />
                        <form method="POST" action="{{ route('wishlist.toggle') }}" class="absolute right-3 top-3 z-10">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $wishlist->product_id }}">
                            <button type="submit" title="นำออกจากรายการโปรด"
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-white/90 text-red-500 shadow transition hover:bg-red-50">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $wishlists->links() }}</div>
        @endif
    </div>
</x-storefront-layout>
