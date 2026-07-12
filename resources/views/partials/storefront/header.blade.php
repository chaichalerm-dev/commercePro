{{-- Top bar --}}
<div class="bg-gray-900 text-gray-200">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-1.5 text-xs sm:px-6 lg:px-8">
        <p class="flex items-center gap-1.5">
            <svg class="h-4 w-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
            {{ __('nav.top_bar.free_shipping', ['amount' => number_format($freeShippingMin)]) }}
        </p>
        <div class="hidden items-center gap-4 sm:flex">
            <a href="{{ route('pages.contact') }}" class="hover:text-white">{{ __('nav.top_bar.help') }}</a>
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="hover:text-white">{{ __('nav.top_bar.track_order') }}</a>
        </div>
    </div>
</div>

<header class="sticky top-0 z-40 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur"
        x-data="{ mobileOpen: false }">
    {{-- Main header row --}}
    <div class="mx-auto flex max-w-7xl items-center gap-4 px-4 py-3 sm:px-6 lg:gap-8 lg:px-8">
        <a href="{{ route('home') }}" class="shrink-0 text-2xl font-bold tracking-tight">
            <span class="text-gray-900">SHOP</span><span class="text-primary-500">SMART</span>
        </a>

        {{-- Search --}}
        <form action="{{ route('products.index') }}" method="GET" class="hidden flex-1 md:block">
            <div class="flex overflow-hidden rounded-full border border-gray-200 bg-gray-50 focus-within:border-primary-400 focus-within:ring-1 focus-within:ring-primary-400">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('nav.search.placeholder') }}"
                       class="w-full border-0 bg-transparent px-5 py-2.5 text-sm focus:ring-0">
                <select name="category" title="{{ __('nav.search.category_label') }}"
                        class="hidden border-0 border-l border-gray-200 bg-transparent py-0 pl-3 pr-8 text-sm text-gray-500 focus:ring-0 lg:block">
                    <option value="">{{ __('nav.search.all_categories') }}</option>
                    @foreach ($navCategories as $navCategory)
                        <option value="{{ $navCategory->slug }}" @selected(request('category') === $navCategory->slug)>{{ $navCategory->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-primary-500 px-5 text-white transition hover:bg-primary-600" aria-label="{{ __('nav.search.button_aria') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </button>
            </div>
        </form>

        {{-- Account + cart --}}
        <div class="ml-auto flex shrink-0 items-center gap-2 md:ml-0">
            <x-language-switcher />

            @auth
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                        <span class="hidden lg:block">{{ auth()->user()->name }}</span>
                    </button>
                    <div x-show="open" x-transition.opacity x-cloak
                         class="absolute right-0 mt-2 w-48 overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-lg">
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('nav.account.admin') }}</a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('nav.account.my_account') }}</a>
                        <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('nav.account.my_orders') }}</a>
                        <a href="{{ route('wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('nav.account.wishlist') }}</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('nav.account.edit_profile') }}</a>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmSubmit(event, '{{ __('nav.account.logout_confirm') }}')">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">{{ __('nav.account.logout') }}</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    <span class="hidden lg:block">{{ __('nav.account.login') }}</span>
                </a>
            @endauth

            <a href="{{ route('cart.index') }}" title="{{ __('nav.cart_link.title') }}" class="relative flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <span class="hidden lg:block">{{ __('nav.cart_link.label') }}</span>
                <span class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-primary-500 px-1 text-[11px] font-bold text-white">{{ $cartCount }}</span>
            </a>

            {{-- Mobile menu toggle --}}
            <button @click="mobileOpen = !mobileOpen" class="rounded-full p-2 text-gray-700 hover:bg-gray-100 md:hidden" aria-label="{{ __('nav.mobile_menu_aria') }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
        </div>
    </div>

    {{-- Nav row (desktop) --}}
    <nav class="hidden border-t border-gray-100 md:block">
        <div class="mx-auto flex max-w-7xl items-center gap-1 px-4 sm:px-6 lg:px-8">
            @foreach ([
                ['label' => __('nav.nav_items.home'), 'url' => route('home'), 'active' => request()->routeIs('home')],
                ['label' => __('nav.nav_items.all_products'), 'url' => route('products.index'), 'active' => request()->routeIs('products.index') && ! request('sort') && ! request()->boolean('on_sale')],
                ['label' => __('nav.nav_items.new_products'), 'url' => route('products.index', ['sort' => 'latest']), 'active' => request('sort') === 'latest'],
                ['label' => __('nav.nav_items.best_sellers'), 'url' => route('products.index', ['sort' => 'popular']), 'active' => request('sort') === 'popular'],
                ['label' => __('nav.nav_items.promotions'), 'url' => route('products.index', ['on_sale' => 1]), 'active' => request()->boolean('on_sale')],
                ['label' => __('nav.nav_items.about'), 'url' => route('pages.about'), 'active' => request()->routeIs('pages.about')],
                ['label' => __('nav.nav_items.contact'), 'url' => route('pages.contact'), 'active' => request()->routeIs('pages.contact')],
            ] as $item)
                <a href="{{ $item['url'] }}"
                   class="border-b-2 px-3 py-2.5 text-sm font-medium transition {{ $item['active'] ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-600 hover:text-primary-600' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Mobile menu --}}
    <div x-show="mobileOpen" x-transition x-cloak class="border-t border-gray-100 bg-white md:hidden">
        <form action="{{ route('products.index') }}" method="GET" class="p-4 pb-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('nav.search.placeholder') }}"
                   class="w-full rounded-full border-gray-200 bg-gray-50 px-5 py-2.5 text-sm focus:border-primary-400 focus:ring-primary-400">
        </form>
        <nav class="space-y-1 p-4 pt-2">
            <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('nav.nav_items.home') }}</a>
            <a href="{{ route('products.index') }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('nav.nav_items.all_products') }}</a>
            <a href="{{ route('products.index', ['on_sale' => 1]) }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">{{ __('nav.nav_items.promotions') }}</a>
            @foreach ($navCategories as $navCategory)
                <a href="{{ route('categories.show', $navCategory->slug) }}" class="block rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">{{ $navCategory->name }}</a>
            @endforeach
        </nav>
    </div>
</header>
