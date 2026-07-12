<x-admin-layout :title="__('admin/products.title')">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.index') }}"
               class="rounded-xl px-4 py-2 text-sm font-medium {{ ! $showTrash ? 'bg-primary-500 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                {{ __('admin/products.tabs.all') }}
            </a>
            <a href="{{ route('admin.products.index', ['view' => 'trash']) }}"
               class="rounded-xl px-4 py-2 text-sm font-medium {{ $showTrash ? 'bg-primary-500 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                {{ __('admin/products.tabs.trash') }} @if($trashCount) <span class="ml-1 rounded-full bg-red-100 px-1.5 text-xs font-bold text-red-500">{{ $trashCount }}</span> @endif
            </a>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="flex items-center gap-2 rounded-xl bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            {{ __('admin/products.form.submit_create') }}
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mt-4 flex flex-wrap items-center gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
        @if ($showTrash)<input type="hidden" name="view" value="trash">@endif
        <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ __('admin/products.filters.search_placeholder') }}"
               class="w-56 rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
        <select name="category" class="rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            <option value="">{{ __('admin/products.filters.all_categories') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            <option value="">{{ __('admin/products.filters.all_statuses') }}</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">{{ __('admin/products.filters.submit') }}</button>
        @if (request()->hasAny(['q', 'category', 'status']))
            <a href="{{ route('admin.products.index', $showTrash ? ['view' => 'trash'] : []) }}" class="text-sm text-gray-400 hover:text-gray-600">{{ __('admin/products.filters.clear') }}</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('admin/products.table.product') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/products.table.category') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/products.table.price') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/products.table.stock') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/products.table.status') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/products.table.featured') }}</th>
                        <th class="px-5 py-3 text-right font-medium">{{ __('admin/products.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->thumbnail_url }}" alt="" loading="lazy" class="h-11 w-11 rounded-lg object-cover">
                                    <div class="min-w-0">
                                        <p class="max-w-52 truncate font-medium text-gray-800">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $product->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $product->category->name }}</td>
                            <td class="px-5 py-3">
                                <p class="font-semibold">{{ money((float) $product->price) }}</p>
                                @if ($product->compare_at_price)
                                    <p class="text-xs text-gray-400 line-through">{{ money((float) $product->compare_at_price) }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="{{ $product->stock < 10 ? 'font-bold text-red-500' : 'text-gray-600' }}">{{ number_format($product->stock) }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ $product->status->color() }}">{{ $product->status->label() }}</span>
                            </td>
                            <td class="px-5 py-3">
                                @unless ($showTrash)
                                    <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" title="{{ __('admin/products.toggle_featured_title') }}"
                                                class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $product->featured ? 'bg-primary-500' : 'bg-gray-200' }}">
                                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition {{ $product->featured ? 'translate-x-[18px]' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>
                                @endunless
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if ($showTrash)
                                        <form method="POST" action="{{ route('admin.products.restore', $product->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" title="{{ __('admin/products.table.restore') }}"
                                                    class="rounded-lg bg-emerald-50 p-2 text-emerald-600 transition hover:bg-emerald-100">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('products.show', $product->slug) }}" target="_blank" title="{{ __('admin/products.view_storefront_title') }}"
                                           class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" title="{{ __('admin/products.table.edit') }}"
                                           class="rounded-lg bg-blue-50 p-2 text-blue-600 transition hover:bg-blue-100">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                              onsubmit="return confirmSubmit(event, '{{ __('admin/products.confirm.delete') }}')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="{{ __('admin/products.table.delete') }}"
                                                    class="rounded-lg bg-red-50 p-2 text-red-500 transition hover:bg-red-100">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">{{ __($showTrash ? 'admin/products.empty_trash' : 'admin/products.empty_state') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">
            {{ $products->links() }}
        </div>
    </div>
</x-admin-layout>
