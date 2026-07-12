<x-storefront-layout :title="__('storefront/orders.index.title')">
    <div class="mx-auto max-w-4xl px-4 py-6 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[__('storefront/orders.index.title') => null]" />
        <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ __('storefront/orders.index.title') }}</h1>

        @if ($orders->isEmpty())
            <div class="mt-8 flex flex-col items-center rounded-2xl border border-dashed border-gray-200 bg-white py-16 text-center">
                <svg class="h-14 w-14 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
                <p class="mt-4 font-medium text-gray-700">{{ __('storefront/orders.index.empty.title') }}</p>
                <a href="{{ route('products.index') }}" class="mt-5 rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-primary-600">{{ __('storefront/orders.index.empty.cta') }}</a>
            </div>
        @else
            <div class="mt-6 space-y-3">
                @foreach ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}"
                       class="flex flex-wrap items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:border-primary-200 hover:shadow-md">
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-primary-600">{{ $order->order_number }}</p>
                            <p class="mt-0.5 text-xs text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }} · {{ __('storefront/orders.index.items_count', ['count' => $order->items_count]) }}</p>
                        </div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $order->status->color() }}">{{ $order->status->label() }}</span>
                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $order->payment_status->color() }}">{{ $order->payment_status->label() }}</span>
                        <span class="text-base font-bold text-gray-900">{{ money((float) $order->grand_total) }}</span>
                    </a>
                @endforeach
            </div>
            <div class="mt-6">{{ $orders->links() }}</div>
        @endif
    </div>
</x-storefront-layout>
