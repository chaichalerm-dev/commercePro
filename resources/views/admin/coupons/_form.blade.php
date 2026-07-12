@php
    /** @var \App\Models\Coupon|null $coupon */
    $coupon ??= null;
@endphp

<form method="POST"
      action="{{ $coupon ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
      class="max-w-2xl space-y-4">
    @csrf
    @if ($coupon) @method('PUT') @endif

    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">{{ __('admin/coupons.form.code_label') }} <span class="text-red-500">*</span></label>
                <input id="code" type="text" name="code" value="{{ old('code', $coupon?->code) }}" required
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm uppercase focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('code')" class="mt-1.5" />
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">{{ __('admin/coupons.form.type_label') }}</label>
                <select id="type" name="type" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" @selected(old('type', $coupon?->type->value) === $type->value)>{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="value" class="block text-sm font-medium text-gray-700">{{ __('admin/coupons.form.value_label') }} <span class="text-red-500">*</span> <span class="text-xs text-gray-400">{{ __('admin/coupons.form.value_hint') }}</span></label>
                <input id="value" type="number" name="value" value="{{ old('value', $coupon?->value) }}" min="0" step="0.01" required
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('value')" class="mt-1.5" />
            </div>

            <div>
                <label for="min_order" class="block text-sm font-medium text-gray-700">{{ __('admin/coupons.form.min_order_label') }}</label>
                <input id="min_order" type="number" name="min_order" value="{{ old('min_order', $coupon?->min_order ?? 0) }}" min="0" step="0.01"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            </div>

            <div>
                <label for="max_uses" class="block text-sm font-medium text-gray-700">{{ __('admin/coupons.form.max_uses_label') }} <span class="text-xs text-gray-400">{{ __('admin/coupons.form.max_uses_hint') }}</span></label>
                <input id="max_uses" type="number" name="max_uses" value="{{ old('max_uses', $coupon?->max_uses) }}" min="1"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('max_uses')" class="mt-1.5" />
            </div>

            <div class="flex items-end pb-2">
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon?->is_active ?? true))
                           class="rounded border-gray-300 text-primary-500 focus:ring-primary-400">
                    <span class="text-sm text-gray-700">{{ __('admin/coupons.form.is_active_label') }}</span>
                </label>
            </div>

            <div>
                <label for="starts_at" class="block text-sm font-medium text-gray-700">{{ __('admin/coupons.form.starts_at_label') }}</label>
                <input id="starts_at" type="date" name="starts_at" value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d')) }}"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            </div>

            <div>
                <label for="expires_at" class="block text-sm font-medium text-gray-700">{{ __('admin/coupons.form.expires_at_label') }}</label>
                <input id="expires_at" type="date" name="expires_at" value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d')) }}"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('expires_at')" class="mt-1.5" />
            </div>
        </div>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
            {{ $coupon ? __('admin/coupons.form.save_changes') : __('admin/coupons.form.submit_create') }}
        </button>
        <a href="{{ route('admin.coupons.index') }}" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm text-gray-600 transition hover:bg-gray-50">{{ __('admin/coupons.form.cancel') }}</a>
    </div>
</form>
