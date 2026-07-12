<x-admin-layout :title="__('admin/orders.title')">
    <form method="GET" class="flex flex-wrap items-center gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('admin/orders.filters.search_placeholder') }}"
               class="w-64 rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
        <select name="status" class="rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            <option value="">{{ __('admin/orders.filters.all_statuses') }}</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <select name="payment" class="rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            <option value="">{{ __('admin/orders.filters.all_payment_statuses') }}</option>
            @foreach ($paymentStatuses as $status)
                <option value="{{ $status->value }}" @selected(request('payment') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">{{ __('admin/orders.filters.submit') }}</button>
        @if (request()->hasAny(['q', 'status', 'payment']))
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-400 hover:text-gray-600">{{ __('admin/orders.filters.clear') }}</a>
        @endif
    </form>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('admin/orders.table.order_number') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/orders.table.customer') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/orders.table.items') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/orders.table.total') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/orders.table.status') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/orders.table.payment') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/orders.table.date') }}</th>
                        <th class="px-5 py-3 text-right font-medium">{{ __('admin/orders.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 py-3 font-medium text-primary-600">{{ $order->order_number }}</td>
                            <td class="px-5 py-3">{{ $order->user->name }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $order->items_count }}</td>
                            <td class="px-5 py-3 font-semibold">{{ money((float) $order->grand_total) }}</td>
                            <td class="px-5 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $order->status->color() }}">{{ $order->status->label() }}</span></td>
                            <td class="px-5 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $order->payment_status->color() }}">{{ $order->payment_status->label() }}</span></td>
                            <td class="px-5 py-3 text-gray-400">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100">{{ __('admin/orders.table.view_details') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">{{ __('admin/orders.empty_state') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">
            {{ $orders->links() }}
        </div>
    </div>
</x-admin-layout>
