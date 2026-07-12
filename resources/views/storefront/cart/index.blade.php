<x-storefront-layout :title="__('storefront/cart.title')">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[__('storefront/cart.title') => null]" />
        <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ __('storefront/cart.title') }}</h1>

        @if ($items->isEmpty())
            <div class="mt-8 flex flex-col items-center rounded-2xl border border-dashed border-gray-200 bg-white py-16 text-center">
                <svg class="h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <p class="mt-4 font-medium text-gray-700">{{ __('storefront/cart.empty.title') }}</p>
                <a href="{{ route('products.index') }}" class="mt-5 rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">{{ __('storefront/cart.empty.cta') }}</a>
            </div>
        @else
            <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_340px]">
                <div class="space-y-3">
                    @foreach ($items as $item)
                        <div class="flex gap-4 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="shrink-0">
                                <img src="{{ $item->product->thumbnail_url }}" alt="{{ $item->product->name }}" loading="lazy" class="h-20 w-20 rounded-xl object-cover sm:h-24 sm:w-24">
                            </a>
                            <div class="flex min-w-0 flex-1 flex-col">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $item->product->slug) }}" class="line-clamp-2 text-sm font-medium text-gray-800 hover:text-primary-600">{{ $item->product->name }}</a>
                                        @if ($item->variant)
                                            <p class="mt-0.5 text-xs text-gray-400">{{ $item->variant->name }}: {{ $item->variant->value }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('cart.destroy', $item->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg p-1.5 text-gray-300 transition hover:bg-red-50 hover:text-red-500" aria-label="{{ __('storefront/cart.remove_aria') }}">
                                            <svg class="h-4.5 w-4.5" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="mt-auto flex items-center justify-between pt-2">
                                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="flex items-center rounded-xl border border-gray-200">
                                        @csrf @method('PATCH')
                                        <button type="submit" name="qty" value="{{ $item->qty - 1 }}" @disabled($item->qty <= 1) class="px-3 py-1.5 text-gray-500 hover:text-primary-600 disabled:opacity-30">−</button>
                                        <span class="w-8 text-center text-sm font-semibold">{{ $item->qty }}</span>
                                        <button type="submit" name="qty" value="{{ $item->qty + 1 }}" class="px-3 py-1.5 text-gray-500 hover:text-primary-600">+</button>
                                    </form>
                                    <p class="text-base font-bold text-primary-600">{{ money($item->line_total) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="h-fit rounded-2xl border border-gray-100 bg-white p-5 shadow-sm lg:sticky lg:top-32">
                    <h2 class="font-semibold text-gray-900">{{ __('storefront/cart.summary_heading') }}</h2>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">{{ __('storefront/cart.subtotal', ['count' => $items->sum('qty')]) }}</dt><dd class="font-medium">{{ money($subtotal) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">{{ __('storefront/cart.shipping') }}</dt><dd class="text-gray-400">{{ __('storefront/cart.shipping_calculated') }}</dd></div>
                    </dl>
                    <div class="mt-4 flex justify-between border-t border-gray-100 pt-4">
                        <span class="font-semibold">{{ __('storefront/cart.total') }}</span>
                        <span class="text-xl font-bold text-primary-600">{{ money($subtotal) }}</span>
                    </div>
                    @auth
                        <a href="{{ route('checkout.show') }}" class="mt-5 block w-full rounded-xl bg-primary-500 py-3 text-center text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">{{ __('storefront/cart.checkout_cta') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-5 block w-full rounded-xl bg-primary-500 py-3 text-center text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">{{ __('storefront/cart.login_cta') }}</a>
                        <p class="mt-2 text-center text-xs text-gray-400">{{ __('storefront/cart.login_note') }}</p>
                    @endauth
                    <a href="{{ route('products.index') }}" class="mt-3 block text-center text-sm text-primary-600 hover:underline">{{ __('storefront/cart.continue_shopping') }}</a>
                </aside>
            </div>
        @endif
    </div>
</x-storefront-layout>
