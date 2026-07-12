<div x-data
     x-show="$store.cartDrawer.open"
     x-cloak
     class="fixed inset-0 z-[90]"
     role="dialog" aria-modal="true">

    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"
         x-show="$store.cartDrawer.open"
         x-transition.opacity
         @click="$store.cartDrawer.close()"></div>

    <div class="absolute inset-y-0 right-0 flex w-full max-w-md flex-col bg-white shadow-2xl"
         x-show="$store.cartDrawer.open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         @keydown.escape.window="$store.cartDrawer.close()">

        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
            <h2 class="text-lg font-bold text-gray-900">{{ __('storefront/cart.title') }}</h2>
            <button type="button" @click="$store.cartDrawer.close()"
                    class="rounded-full p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600" aria-label="{{ __('common.close') }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-4">
            <div x-show="$store.cartDrawer.loading" class="flex justify-center py-12">
                <svg class="h-6 w-6 animate-spin text-gray-300" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </div>

            <div x-show="!$store.cartDrawer.loading && $store.cartDrawer.items.length === 0" class="flex flex-col items-center py-16 text-center">
                <svg class="h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <p class="mt-4 font-medium text-gray-700">{{ __('storefront/cart.empty.title') }}</p>
                <a href="{{ route('products.index') }}" @click="$store.cartDrawer.close()"
                   class="mt-5 rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">{{ __('storefront/cart.empty.cta') }}</a>
            </div>

            <div class="space-y-3" x-show="!$store.cartDrawer.loading && $store.cartDrawer.items.length > 0">
                <template x-for="item in $store.cartDrawer.items" :key="item.id">
                    <div class="flex gap-3 rounded-xl border border-gray-100 p-3">
                        <a :href="item.url" @click="$store.cartDrawer.close()" class="shrink-0">
                            <img :src="item.thumbnail" :alt="item.name" loading="lazy" class="h-16 w-16 rounded-lg object-cover">
                        </a>
                        <div class="flex min-w-0 flex-1 flex-col">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <a :href="item.url" @click="$store.cartDrawer.close()" class="line-clamp-2 text-sm font-medium text-gray-800 hover:text-primary-600" x-text="item.name"></a>
                                    <p class="mt-0.5 text-xs text-gray-400" x-show="item.variant" x-text="item.variant"></p>
                                </div>
                                <button type="button" @click="$store.cartDrawer.remove(item.id)" :disabled="$store.cartDrawer.pendingIds.includes(item.id)"
                                        class="rounded-lg p-1 text-gray-300 transition hover:bg-red-50 hover:text-red-500 disabled:opacity-30" aria-label="{{ __('storefront/cart.remove_aria') }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </div>
                            <div class="mt-auto flex items-center justify-between pt-2">
                                <div class="flex items-center rounded-lg border border-gray-200">
                                    <button type="button" @click="$store.cartDrawer.updateQty(item.id, item.qty - 1)" :disabled="item.qty <= 1 || $store.cartDrawer.pendingIds.includes(item.id)"
                                            class="px-2.5 py-1 text-gray-500 hover:text-primary-600 disabled:opacity-30">−</button>
                                    <span class="w-6 text-center text-xs font-semibold" x-text="item.qty"></span>
                                    <button type="button" @click="$store.cartDrawer.updateQty(item.id, item.qty + 1)" :disabled="item.qty >= item.maxQty || $store.cartDrawer.pendingIds.includes(item.id)"
                                            class="px-2.5 py-1 text-gray-500 hover:text-primary-600 disabled:opacity-30">+</button>
                                </div>
                                <p class="text-sm font-bold text-primary-600" x-text="item.lineTotal"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="border-t border-gray-100 px-5 py-4" x-show="!$store.cartDrawer.loading && $store.cartDrawer.items.length > 0">
            <div class="flex justify-between text-sm">
                <span class="font-semibold text-gray-900">{{ __('storefront/cart.total') }}</span>
                <span class="text-lg font-bold text-primary-600" x-text="$store.cartDrawer.subtotal"></span>
            </div>
            @auth
                <a href="{{ route('checkout.show') }}" class="mt-4 block w-full rounded-xl bg-primary-500 py-3 text-center text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">{{ __('storefront/cart.checkout_cta') }}</a>
            @else
                <a href="{{ route('login') }}" class="mt-4 block w-full rounded-xl bg-primary-500 py-3 text-center text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">{{ __('storefront/cart.login_cta') }}</a>
            @endauth
            <a href="{{ route('cart.index') }}" @click="$store.cartDrawer.close()" class="mt-3 block text-center text-sm text-primary-600 hover:underline">{{ __('storefront/cart.view_full_cart') }}</a>
        </div>
    </div>
</div>
