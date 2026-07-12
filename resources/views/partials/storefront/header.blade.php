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
                         class="absolute right-0 mt-2 w-64 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg">
                        <div class="flex items-center gap-3 border-b border-gray-100 px-4 py-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="truncate text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        @if (auth()->user()->isAdmin())
                            <div class="border-b border-gray-100 py-1">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                    {{ __('nav.account.admin') }}
                                </a>
                            </div>
                        @endif

                        <div class="border-b border-gray-100 py-1">
                            <p class="px-4 pb-1 pt-1.5 text-[11px] font-semibold uppercase tracking-wide text-gray-400">{{ __('nav.account.section_account') }}</p>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                                {{ __('nav.account.my_account') }}
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                {{ __('nav.account.edit_profile') }}
                            </a>
                        </div>

                        <div class="border-b border-gray-100 py-1">
                            <p class="px-4 pb-1 pt-1.5 text-[11px] font-semibold uppercase tracking-wide text-gray-400">{{ __('nav.account.section_shopping') }}</p>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                                {{ __('nav.account.my_orders') }}
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                                {{ __('nav.account.wishlist') }}
                            </a>
                        </div>

                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmSubmit(event, '{{ __('nav.account.logout_confirm') }}')">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                    <svg class="h-5 w-5 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H3.75"/></svg>
                                    {{ __('nav.account.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    <span class="hidden lg:block">{{ __('nav.account.login') }}</span>
                </a>
            @endauth

            <a href="{{ route('cart.index') }}" title="{{ __('nav.cart_link.title') }}"
               x-init="$store.cartDrawer.count = {{ $cartCount }}"
               @click.prevent="$store.cartDrawer.show()"
               class="relative flex items-center gap-2 rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                <span class="hidden lg:block">{{ __('nav.cart_link.label') }}</span>
                <span class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-primary-500 px-1 text-[11px] font-bold text-white" x-text="$store.cartDrawer.count">{{ $cartCount }}</span>
            </a>

            {{-- Mobile menu toggle --}}
            <button @click="mobileOpen = !mobileOpen" class="rounded-full p-2 text-gray-700 hover:bg-gray-100 md:hidden" aria-label="{{ __('nav.mobile_menu_aria') }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>
        </div>
    </div>

    @php
        $navItems = [
            ['label' => __('nav.nav_items.home'), 'url' => route('home'), 'active' => request()->routeIs('home')],
            ['label' => __('nav.nav_items.all_products'), 'url' => route('products.index'), 'active' => request()->routeIs('products.index') && ! request('sort') && ! request()->boolean('on_sale')],
            ['label' => __('nav.nav_items.new_products'), 'url' => route('products.index', ['sort' => 'latest']), 'active' => request('sort') === 'latest'],
            ['label' => __('nav.nav_items.best_sellers'), 'url' => route('products.index', ['sort' => 'popular']), 'active' => request('sort') === 'popular'],
            ['label' => __('nav.nav_items.promotions'), 'url' => route('products.index', ['on_sale' => 1]), 'active' => request()->boolean('on_sale')],
            ['label' => __('nav.nav_items.about'), 'url' => route('pages.about'), 'active' => request()->routeIs('pages.about')],
            ['label' => __('nav.nav_items.contact'), 'url' => route('pages.contact'), 'active' => request()->routeIs('pages.contact')],
        ];
    @endphp

    {{-- Nav row (desktop) --}}
    <nav class="hidden border-t border-gray-100 md:block">
        <div class="mx-auto flex max-w-7xl items-center gap-1 px-4 sm:px-6 lg:px-8">
            @foreach ($navItems as $item)
                <a href="{{ $item['url'] }}"
                   class="border-b-2 px-3 py-2.5 text-sm font-medium transition {{ $item['active'] ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-600 hover:text-primary-600' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>

    {{-- Mobile menu --}}
    <div x-show="mobileOpen" x-transition x-cloak class="max-h-[calc(100vh-4rem)] overflow-y-auto border-t border-gray-100 bg-white md:hidden">
        <form action="{{ route('products.index') }}" method="GET" class="p-4 pb-2">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('nav.search.placeholder') }}"
                   class="w-full rounded-full border-gray-200 bg-gray-50 px-5 py-2.5 text-sm focus:border-primary-400 focus:ring-primary-400">
        </form>

        <nav class="grid grid-cols-2 gap-2 p-4 pt-2">
            @foreach ($navItems as $item)
                <a href="{{ $item['url'] }}"
                   class="truncate rounded-lg px-3 py-2.5 text-center text-sm font-medium transition {{ $item['active'] ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        @if ($navCategories->isNotEmpty())
            <div class="border-t border-gray-100 p-4 pt-3">
                <p class="px-1 pb-2 text-xs font-semibold uppercase tracking-wide text-gray-400">{{ __('nav.categories_heading') }}</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach ($navCategories as $navCategory)
                        <a href="{{ route('categories.show', $navCategory->slug) }}"
                           class="truncate rounded-lg border border-gray-200 px-3 py-2.5 text-center text-sm text-gray-600 transition hover:border-primary-300 hover:bg-primary-50 hover:text-primary-600">
                            {{ $navCategory->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</header>
