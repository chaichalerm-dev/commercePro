@props(['title' => null])

@php
    $title ??= __('admin/layout.default_title');

    $menuGroups = [
        __('admin/layout.group.overview') => [
            ['label' => __('admin/layout.item.dashboard'), 'route' => 'admin.dashboard', 'ability' => null, 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
        ],
        __('admin/layout.group.catalog') => [
            ['label' => __('admin/layout.item.products'), 'route' => 'admin.products.index', 'ability' => 'products', 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
            ['label' => __('admin/layout.item.categories'), 'route' => 'admin.categories.index', 'ability' => 'categories', 'icon' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z M6 6h.008v.008H6V6z'],
        ],
        __('admin/layout.group.sales') => [
            ['label' => __('admin/layout.item.orders'), 'route' => 'admin.orders.index', 'ability' => 'orders', 'icon' => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z'],
            ['label' => __('admin/layout.item.coupons'), 'route' => 'admin.coupons.index', 'ability' => 'coupons', 'icon' => 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-12-.75h16.5a1.5 1.5 0 001.5-1.5V15a3 3 0 110-6V7.5a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5V9a3 3 0 110 6v.75a1.5 1.5 0 001.5 1.5z'],
            ['label' => __('admin/layout.item.banners'), 'route' => 'admin.banners.index', 'ability' => 'banners', 'icon' => 'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z'],
        ],
        __('admin/layout.group.customers_reviews') => [
            ['label' => __('admin/layout.item.customers'), 'route' => 'admin.customers.index', 'ability' => 'customers', 'icon' => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
            ['label' => __('admin/layout.item.reviews'), 'route' => 'admin.reviews.index', 'ability' => 'reviews', 'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z'],
        ],
        __('admin/layout.group.system') => [
            ['label' => __('admin/layout.item.users'), 'route' => 'admin.users.index', 'ability' => 'users', 'icon' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => __('admin/layout.item.settings'), 'route' => 'admin.settings.index', 'ability' => 'settings', 'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['label' => __('admin/layout.item.logs'), 'route' => 'admin.logs.index', 'ability' => 'logs', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
        ],
    ];

    // Drop links (and whole groups) the current admin's role can't reach.
    $menuGroups = collect($menuGroups)
        ->map(fn (array $items) => array_values(array_filter(
            $items,
            fn (array $item) => $item['ability'] === null || Illuminate\Support\Facades\Gate::allows($item['ability']),
        )))
        ->filter(fn (array $items) => $items !== [])
        ->all();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ \App\Models\Setting::url('favicon') ?? asset('favicon.ico') }}">

    <title>{{ $title }} | ShopSmart Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|noto-sans-thai:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
</head>
<body class="min-h-screen bg-gray-100 font-sans text-gray-800 antialiased"
      x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('admin_sidebar_collapsed') === '1' }">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col bg-gray-900 transition-all lg:translate-x-0"
           :class="[sidebarOpen && '!translate-x-0', sidebarCollapsed ? 'lg:w-20' : 'lg:w-64']">
        <div class="flex h-16 shrink-0 items-center gap-2 border-b border-gray-800 px-6" :class="sidebarCollapsed && 'lg:justify-center lg:px-0'">
            <a href="{{ route('admin.dashboard') }}" class="shrink-0 text-xl font-bold tracking-tight" :class="sidebarCollapsed && 'lg:hidden'">
                <span class="text-white">SHOP</span><span class="text-primary-500">SMART</span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="hidden text-xl font-bold text-primary-500" :class="sidebarCollapsed && 'lg:block'" x-cloak>S</a>
            <span class="rounded bg-primary-500/15 px-1.5 py-0.5 text-[10px] font-bold uppercase text-primary-400" :class="sidebarCollapsed && 'lg:hidden'">Admin</span>
        </div>

        <nav class="sidebar-scroll min-h-0 flex-1 space-y-4 overflow-y-auto p-3">
            @foreach ($menuGroups as $groupLabel => $items)
                <div class="space-y-0.5">
                    <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-500" :class="sidebarCollapsed && 'lg:hidden'">{{ $groupLabel }}</p>
                    @foreach ($items as $item)
                        @if (Illuminate\Support\Facades\Route::has($item['route']))
                            <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}"
                               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'bg-primary-500 text-white shadow' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                               :class="sidebarCollapsed && 'lg:justify-center lg:px-0'">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                <span :class="sidebarCollapsed && 'lg:hidden'">{{ $item['label'] }}</span>
                            </a>
                        @else
                            <span class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-600" title="{{ __('admin/layout.coming_soon.title') }}"
                                  :class="sidebarCollapsed && 'lg:justify-center lg:px-0'">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
                                <span :class="sidebarCollapsed && 'lg:hidden'">{{ $item['label'] }}</span>
                                <span class="ml-auto rounded bg-gray-800 px-1.5 py-0.5 text-[10px] text-gray-500" :class="sidebarCollapsed && 'lg:hidden'">{{ __('admin/layout.coming_soon.badge') }}</span>
                            </span>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </nav>

        {{-- Collapse toggle (desktop only — the mobile off-canvas menu closes via the overlay instead) --}}
        <div class="hidden shrink-0 border-t border-gray-800 p-3 lg:block">
            <button @click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('admin_sidebar_collapsed', sidebarCollapsed ? '1' : '0')"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-400 transition hover:bg-gray-800 hover:text-white"
                    :class="sidebarCollapsed && 'lg:justify-center lg:px-0'"
                    :title="sidebarCollapsed ? '{{ __('admin/layout.sidebar.expand') }}' : '{{ __('admin/layout.sidebar.collapse') }}'">
                <svg class="h-5 w-5 shrink-0 transition-transform" :class="sidebarCollapsed && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"/></svg>
                <span :class="sidebarCollapsed && 'lg:hidden'">{{ __('admin/layout.sidebar.collapse') }}</span>
            </button>
        </div>
    </aside>

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

    <div class="transition-all lg:pl-64" :class="sidebarCollapsed && 'lg:!pl-20'">
        {{-- Topbar --}}
        <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 sm:px-6">
            <button @click="sidebarOpen = true" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 lg:hidden" aria-label="{{ __('admin/layout.topbar.open_menu') }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
            </button>

            <h1 class="text-lg font-semibold text-gray-900">{{ $title }}</h1>

            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('home') }}" target="_blank"
                   class="hidden items-center gap-1.5 rounded-lg px-3 py-2 text-sm text-gray-500 transition hover:bg-gray-100 sm:flex">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    {{ __('admin/layout.topbar.view_storefront') }}
                </a>

                <x-language-switcher />

                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 rounded-full py-1.5 pl-1.5 pr-3 transition hover:bg-gray-100">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-bold text-primary-600">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </span>
                        <span class="hidden text-sm font-medium text-gray-700 sm:block">{{ auth()->user()->name }}</span>
                    </button>
                    <div x-show="open" x-transition.opacity x-cloak
                         class="absolute right-0 mt-2 w-44 overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-lg">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('admin/layout.topbar.profile') }}</a>
                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirmSubmit(event, '{{ __('admin/layout.topbar.confirm_logout') }}')">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">{{ __('admin/layout.topbar.logout') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash toast --}}
        @if (session('success') || session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition.opacity.duration.300ms x-cloak
                 class="fixed right-4 top-20 z-50 flex max-w-sm items-center gap-3 rounded-xl border p-4 shadow-lg {{ session('success') ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-red-100 bg-red-50 text-red-600' }}">
                @if (session('success'))
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                @endif
                <p class="text-sm font-medium">{{ session('success') ?? session('error') }}</p>
                <button @click="show = false" class="ml-auto opacity-60 hover:opacity-100" aria-label="{{ __('admin/layout.topbar.close') }}">✕</button>
            </div>
        @endif

        <main class="p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>

    <x-confirm-dialog />
</body>
</html>
