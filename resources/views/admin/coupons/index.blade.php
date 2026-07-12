<x-admin-layout :title="__('admin/coupons.title')">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ __('admin/coupons.total_count', ['count' => $coupons->total()]) }}</p>
        <a href="{{ route('admin.coupons.create') }}"
           class="flex items-center gap-2 rounded-xl bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            {{ __('admin/coupons.form.submit_create') }}
        </a>
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('admin/coupons.table.code') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/coupons.table.discount') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/coupons.table.min_order') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/coupons.table.used_count') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/coupons.table.expires_at') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/coupons.table.status') }}</th>
                        <th class="px-5 py-3 text-right font-medium">{{ __('admin/coupons.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($coupons as $coupon)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 py-3"><code class="rounded bg-primary-50 px-2 py-1 text-xs font-bold text-primary-600">{{ $coupon->code }}</code></td>
                            <td class="px-5 py-3 font-semibold">
                                {{ $coupon->type === \App\Enums\CouponType::Percent ? number_format((float) $coupon->value).'%' : money((float) $coupon->value) }}
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ (float) $coupon->min_order > 0 ? money((float) $coupon->min_order) : '-' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ number_format($coupon->used_count) }}{{ $coupon->max_uses ? ' / '.number_format($coupon->max_uses) : '' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $coupon->expires_at?->format('d/m/Y') ?? __('admin/coupons.no_expiry') }}</td>
                            <td class="px-5 py-3">
                                @php $usable = $coupon->isRedeemable(PHP_FLOAT_MAX); @endphp
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $usable ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $usable ? __('admin/coupons.status.usable') : ($coupon->is_active ? __('admin/coupons.status.expired_or_full') : __('admin/coupons.status.disabled')) }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100">{{ __('admin/coupons.table.edit') }}</a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" onsubmit="return confirmSubmit(event, '{{ __('admin/coupons.confirm.delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-100">{{ __('admin/coupons.table.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">{{ __('admin/coupons.empty_state') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">{{ $coupons->links() }}</div>
    </div>
</x-admin-layout>
