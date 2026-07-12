@php
    /** @var \App\Models\Category|null $category */
    $category ??= null;
@endphp

<form method="POST"
      action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      enctype="multipart/form-data"
      class="max-w-2xl space-y-4">
    @csrf
    @if ($category) @method('PUT') @endif

    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('admin/categories.fields.name') }} <span class="text-red-500">*</span></label>
                <input id="name" type="text" name="name" value="{{ old('name', $category?->name) }}" required
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">{{ __('admin/categories.form.slug') }} <span class="text-xs text-gray-400">{{ __('admin/categories.form.slug_hint') }}</span></label>
                <input id="slug" type="text" name="slug" value="{{ old('slug', $category?->slug) }}"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('slug')" class="mt-1.5" />
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700">{{ __('admin/categories.form.sort_order') }}</label>
                <input id="sort_order" type="number" name="sort_order" value="{{ old('sort_order', $category?->sort_order ?? 0) }}" min="0"
                       class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('sort_order')" class="mt-1.5" />
            </div>

            <div class="flex items-end pb-2">
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category?->is_active ?? true))
                           class="rounded border-gray-300 text-primary-500 focus:ring-primary-400">
                    <span class="text-sm text-gray-700">{{ __('admin/categories.form.is_active_label') }}</span>
                </label>
            </div>
        </div>

        <div class="mt-4" x-data="{ preview: {{ Js::from($category?->image_url) }} }">
            <label class="block text-sm font-medium text-gray-700">{{ __('admin/categories.form.category_image_label') }}</label>
            <template x-if="preview">
                <img :src="preview" alt="" class="mt-2 h-28 w-28 rounded-xl object-cover">
            </template>
            <input type="file" name="image" accept="image/*"
                   class="mt-2 w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-600 hover:file:bg-primary-100"
                   @change="const f = $event.target.files[0]; if (f) preview = URL.createObjectURL(f)">
            <x-input-error :messages="$errors->get('image')" class="mt-1.5" />
        </div>
    </div>

    <div class="flex gap-2">
        <button type="submit" class="rounded-xl bg-primary-500 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
            {{ $category ? __('admin/categories.form.save_changes') : __('admin/categories.form.submit_create') }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm text-gray-600 transition hover:bg-gray-50">{{ __('admin/categories.form.cancel') }}</a>
    </div>
</form>
