@php
    /** @var \App\Models\Banner|null $banner */
    $banner ??= null;
@endphp

<form method="POST"
      action="{{ $banner ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
      enctype="multipart/form-data"
      class="max-w-2xl space-y-4">
    @csrf
    @if ($banner) @method('PUT') @endif

    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="title" class="block text-sm font-medium text-gray-700">{{ __('admin/banners.form.title_label') }} <span class="text-red-500">*</span></label>
                <input id="title" type="text" name="title" value="{{ old('title', $banner?->title) }}" required
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
            </div>

            <div class="sm:col-span-2">
                <label for="subtitle" class="block text-sm font-medium text-gray-700">{{ __('admin/banners.form.subtitle_label') }}</label>
                <input id="subtitle" type="text" name="subtitle" value="{{ old('subtitle', $banner?->subtitle) }}"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            </div>

            <div class="sm:col-span-2">
                <label class="flex cursor-pointer items-start gap-2">
                    <input type="checkbox" name="show_title" value="1" @checked(old('show_title', $banner?->show_title ?? true))
                           class="mt-0.5 rounded border-gray-300 text-primary-500 focus:ring-primary-400">
                    <span>
                        <span class="block text-sm font-medium text-gray-700">{{ __('admin/banners.form.show_title_label') }}</span>
                        <span class="block text-xs text-gray-400">{{ __('admin/banners.form.show_title_hint') }}</span>
                    </span>
                </label>
            </div>

            <div>
                <label for="link" class="block text-sm font-medium text-gray-700">{{ __('admin/banners.form.link_label') }}</label>
                <input id="link" type="text" name="link" value="{{ old('link', $banner?->link) }}" placeholder="/products?on_sale=1"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            </div>

            <div>
                <label for="position" class="block text-sm font-medium text-gray-700">{{ __('admin/banners.form.position_label') }}</label>
                <select id="position" name="position" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                    @foreach ($positions as $position)
                        <option value="{{ $position->value }}" @selected(old('position', $banner?->position->value) === $position->value)>{{ $position->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700">{{ __('admin/banners.form.sort_order_label') }}</label>
                <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $banner?->sort_order ?? 0) }}" min="0"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            </div>

            <div class="flex items-end pb-2">
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner?->is_active ?? true))
                           class="rounded border-gray-300 text-primary-500 focus:ring-primary-400">
                    <span class="text-sm text-gray-700">{{ __('admin/banners.form.is_active_label') }}</span>
                </label>
            </div>
        </div>

        <div class="mt-4" x-data="{ preview: {{ Js::from($banner?->image_url) }} }">
            <label class="block text-sm font-medium text-gray-700">{{ __('admin/banners.form.image_label') }} {{ $banner ? '' : '*' }} <span class="text-xs text-gray-400">{{ __('admin/banners.form.image_hint') }}</span></label>
            <template x-if="preview">
                <img :src="preview" alt="" class="mt-2 aspect-[8/3] w-full rounded-xl object-cover">
            </template>
            <input type="file" name="image" accept="image/*"
                   class="mt-2 w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-600 hover:file:bg-primary-100"
                   @change="const f = $event.target.files[0]; if (f) preview = URL.createObjectURL(f)">
            <x-input-error :messages="$errors->get('image')" class="mt-1.5" />
        </div>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
            {{ $banner ? __('admin/banners.form.save_changes') : __('admin/banners.form.submit_create') }}
        </button>
        <a href="{{ route('admin.banners.index') }}" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm text-gray-600 transition hover:bg-gray-50">{{ __('admin/banners.form.cancel') }}</a>
    </div>
</form>
