@php
    /** @var \App\Models\Product|null $product */
    $product ??= null;
    $initialVariants = old('variants', $product?->variants->map(fn ($v) => [
        'id' => $v->id, 'name' => $v->name, 'value' => $v->value,
        'price_modifier' => (float) $v->price_modifier, 'stock' => $v->stock,
    ])->values()->all() ?? []);
@endphp

<form method="POST"
      action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      x-data="{ variants: {{ Js::from($initialVariants) }} }"
      class="grid gap-4 lg:grid-cols-3">
    @csrf
    @if ($product) @method('PUT') @endif

    <div class="space-y-4 lg:col-span-2">
        {{-- Basic info --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-gray-900">{{ __('admin/products.form.section_basic_info') }}</h2>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('admin/products.fields.name') }} <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $product?->name) }}" required
                           class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700">{{ __('admin/products.form.slug') }} <span class="text-xs text-gray-400">{{ __('admin/products.form.slug_hint') }}</span></label>
                    <input id="slug" type="text" name="slug" value="{{ old('slug', $product?->slug) }}"
                           class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                    <x-input-error :messages="$errors->get('slug')" class="mt-1.5" />
                </div>

                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700">{{ __('admin/products.form.sku') }} <span class="text-xs text-gray-400">{{ __('admin/products.form.sku_hint') }}</span></label>
                    <input id="sku" type="text" name="sku" value="{{ old('sku', $product?->sku) }}"
                           class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                    <x-input-error :messages="$errors->get('sku')" class="mt-1.5" />
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ __('admin/products.form.description') }}</label>
                    <textarea id="description" name="description" rows="6"
                              class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">{{ old('description', $product?->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                </div>
            </div>
        </div>

        {{-- Pricing & stock --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-gray-900">{{ __('admin/products.form.section_pricing_stock') }}</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">{{ __('admin/products.form.selling_price') }} <span class="text-red-500">*</span></label>
                    <input id="price" type="number" name="price" value="{{ old('price', $product?->price) }}" min="0" step="0.01" required
                           class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                    <x-input-error :messages="$errors->get('price')" class="mt-1.5" />
                </div>
                <div>
                    <label for="compare_at_price" class="block text-sm font-medium text-gray-700">{{ __('admin/products.fields.compare_at_price') }} <span class="text-xs text-gray-400">{{ __('admin/products.form.compare_at_price_hint') }}</span></label>
                    <input id="compare_at_price" type="number" name="compare_at_price" value="{{ old('compare_at_price', $product?->compare_at_price) }}" min="0" step="0.01"
                           class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                    <x-input-error :messages="$errors->get('compare_at_price')" class="mt-1.5" />
                </div>
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700">{{ __('admin/products.fields.stock') }} <span class="text-red-500">*</span></label>
                    <input id="stock" type="number" name="stock" value="{{ old('stock', $product?->stock ?? 0) }}" min="0" required
                           class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                    <x-input-error :messages="$errors->get('stock')" class="mt-1.5" />
                </div>
            </div>
        </div>

        {{-- Variants --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">{{ __('admin/products.form.section_variants') }}</h2>
                <button type="button" @click="variants.push({ id: null, name: '', value: '', price_modifier: 0, stock: 0 })"
                        class="rounded-lg bg-primary-50 px-3 py-1.5 text-xs font-semibold text-primary-600 transition hover:bg-primary-100">
                    {{ __('admin/products.form.add_variant') }}
                </button>
            </div>
            <x-input-error :messages="$errors->get('variants')" class="mt-1.5" />

            <div class="mt-3 space-y-2">
                <template x-for="(variant, index) in variants" :key="index">
                    <div class="grid grid-cols-2 items-end gap-2 rounded-xl bg-gray-50 p-3 sm:grid-cols-[1fr_1fr_120px_100px_auto]">
                        <input type="hidden" :name="`variants[${index}][id]`" :value="variant.id ?? ''">
                        <div>
                            <label class="text-xs text-gray-500">{{ __('admin/products.form.variant_name_label') }}</label>
                            <input type="text" :name="`variants[${index}][name]`" x-model="variant.name" placeholder="Size"
                                   class="mt-1 w-full rounded-lg border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">{{ __('admin/products.form.variant_value_label') }}</label>
                            <input type="text" :name="`variants[${index}][value]`" x-model="variant.value" placeholder="XL"
                                   class="mt-1 w-full rounded-lg border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">{{ __('admin/products.form.variant_price_modifier_label') }}</label>
                            <input type="number" :name="`variants[${index}][price_modifier]`" x-model="variant.price_modifier" min="0" step="0.01"
                                   class="mt-1 w-full rounded-lg border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500">{{ __('admin/products.fields.stock') }}</label>
                            <input type="number" :name="`variants[${index}][stock]`" x-model="variant.stock" min="0"
                                   class="mt-1 w-full rounded-lg border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                        </div>
                        <button type="button" @click="variants.splice(index, 1)"
                                class="rounded-lg p-2 text-red-400 transition hover:bg-red-50 hover:text-red-600" aria-label="{{ __('admin/products.form.remove_variant') }}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        </button>
                    </div>
                </template>
                <p x-show="variants.length === 0" class="text-sm text-gray-400">{{ __('admin/products.form.no_variants') }}</p>
            </div>
        </div>
    </div>

    {{-- Sidebar column --}}
    <div class="space-y-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-gray-900">{{ __('admin/products.form.section_publishing') }}</h2>

            <label for="category_id" class="mt-4 block text-sm font-medium text-gray-700">{{ __('admin/products.fields.category_id') }} <span class="text-red-500">*</span></label>
            <select id="category_id" name="category_id" required
                    class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                <option value="">{{ __('admin/products.form.select_category_placeholder') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('category_id')" class="mt-1.5" />

            <label for="status" class="mt-4 block text-sm font-medium text-gray-700">{{ __('admin/products.form.status_label') }}</label>
            <select id="status" name="status" class="mt-1.5 w-full rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $product?->status->value ?? 'draft') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>

            <label class="mt-4 flex cursor-pointer items-center gap-2">
                <input type="checkbox" name="featured" value="1" @checked(old('featured', $product?->featured))
                       class="rounded border-gray-300 text-primary-500 focus:ring-primary-400">
                <span class="text-sm text-gray-700">{{ __('admin/products.form.featured_label') }}</span>
            </label>
        </div>

        {{-- Thumbnail --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"
             x-data="{ preview: {{ Js::from($product?->thumbnail_url) }} }">
            <h2 class="font-semibold text-gray-900">{{ __('admin/products.fields.thumbnail') }}</h2>
            <template x-if="preview">
                <img :src="preview" alt="" class="mt-3 aspect-square w-full rounded-xl object-cover">
            </template>
            <input type="file" name="thumbnail" accept="image/*" class="mt-3 w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-600 hover:file:bg-primary-100"
                   @change="const f = $event.target.files[0]; if (f) preview = URL.createObjectURL(f)">
            <x-input-error :messages="$errors->get('thumbnail')" class="mt-1.5" />
        </div>

        {{-- Gallery --}}
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="font-semibold text-gray-900">{{ __('admin/products.fields.images') }} <span class="text-xs font-normal text-gray-400">{{ __('admin/products.form.images_hint_max') }}</span></h2>

            @if ($product?->images->isNotEmpty())
                <div class="mt-3 grid grid-cols-3 gap-2">
                    @foreach ($product->images as $image)
                        <label class="group relative block cursor-pointer" title="{{ __('admin/products.form.tick_to_remove_image') }}">
                            <img src="{{ $image->url }}" alt="" loading="lazy" class="aspect-square w-full rounded-xl object-cover">
                            <input type="checkbox" name="removed_images[]" value="{{ $image->id }}"
                                   class="peer absolute right-1.5 top-1.5 rounded border-gray-300 text-red-500 focus:ring-red-400">
                            <span class="pointer-events-none absolute inset-0 hidden rounded-xl bg-red-500/30 ring-2 ring-red-500 peer-checked:block"></span>
                        </label>
                    @endforeach
                </div>
                <p class="mt-2 text-xs text-gray-400">{{ __('admin/products.form.remove_image_note') }}</p>
            @endif

            <input type="file" name="images[]" accept="image/*" multiple
                   class="mt-3 w-full text-sm text-gray-500 file:mr-3 file:rounded-lg file:border-0 file:bg-primary-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-primary-600 hover:file:bg-primary-100">
            <x-input-error :messages="$errors->get('images')" class="mt-1.5" />
            <x-input-error :messages="$errors->get('images.*')" class="mt-1.5" />
        </div>

        <div class="flex gap-2">
            <button type="submit" class="flex-1 rounded-xl bg-primary-500 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
                {{ $product ? __('admin/products.form.save_changes') : __('admin/products.form.submit_create') }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm text-gray-600 transition hover:bg-gray-50">{{ __('admin/products.form.cancel') }}</a>
        </div>
    </div>
</form>
