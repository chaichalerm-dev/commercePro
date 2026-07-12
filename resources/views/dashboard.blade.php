<x-storefront-layout :title="__('dashboard.title')">
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('dashboard.greeting', ['name' => auth()->user()->name]) }} 👋</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('dashboard.subtitle') }}</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <a href="{{ route('orders.index') }}" class="group rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:border-primary-200 hover:shadow-md">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                </span>
                <p class="mt-3 font-semibold text-gray-800 group-hover:text-primary-600">{{ __('dashboard.my_orders') }}</p>
                <p class="mt-0.5 text-xs text-gray-400">{{ __('dashboard.items_count', ['count' => auth()->user()->orders()->count()]) }}</p>
            </a>
            <a href="{{ route('wishlist.index') }}" class="group rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:border-primary-200 hover:shadow-md">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                </span>
                <p class="mt-3 font-semibold text-gray-800 group-hover:text-primary-600">{{ __('dashboard.wishlist') }}</p>
                <p class="mt-0.5 text-xs text-gray-400">{{ __('dashboard.items_count', ['count' => auth()->user()->wishlists()->count()]) }}</p>
            </a>
            <a href="{{ route('profile.edit') }}" class="group rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:border-primary-200 hover:shadow-md">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </span>
                <p class="mt-3 font-semibold text-gray-800 group-hover:text-primary-600">{{ __('dashboard.edit_profile') }}</p>
                <p class="mt-0.5 text-xs text-gray-400">{{ auth()->user()->email }}</p>
            </a>
        </div>

        @php $recentOrders = auth()->user()->orders()->withCount('items')->latest()->limit(5)->get(); @endphp
        @if ($recentOrders->isNotEmpty())
            <section class="mt-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('dashboard.recent_orders_heading') }}</h2>
                    <a href="{{ route('orders.index') }}" class="text-sm font-medium text-primary-600 hover:underline">{{ __('dashboard.view_all') }}</a>
                </div>
                <div class="mt-3 space-y-2">
                    @foreach ($recentOrders as $order)
                        <a href="{{ route('orders.show', $order) }}"
                           class="flex flex-wrap items-center gap-3 rounded-2xl border border-gray-100 bg-white px-5 py-3.5 shadow-sm transition hover:border-primary-200">
                            <span class="min-w-0 flex-1 truncate text-sm font-semibold text-primary-600">{{ $order->order_number }}</span>
                            <span class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y') }}</span>
                            <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ $order->status->color() }}">{{ $order->status->label() }}</span>
                            <span class="text-sm font-bold">{{ money((float) $order->grand_total) }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-storefront-layout>
