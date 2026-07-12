<x-admin-layout :title="__('admin/categories.title')">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ __('admin/categories.total_count', ['count' => $categories->total()]) }}</p>
        <a href="{{ route('admin.categories.create') }}"
           class="flex items-center gap-2 rounded-xl bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            {{ __('admin/categories.form.submit_create') }}
        </a>
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">{{ __('admin/categories.table.category') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/categories.table.slug') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/categories.table.products_count') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/categories.table.sort_order') }}</th>
                        <th class="px-5 py-3 font-medium">{{ __('admin/categories.table.status') }}</th>
                        <th class="px-5 py-3 text-right font-medium">{{ __('admin/categories.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50/60">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $category->image_url }}" alt="" loading="lazy" class="h-10 w-10 rounded-lg object-cover">
                                    <span class="font-medium text-gray-800">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-gray-400">{{ $category->slug }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ number_format($category->products_count) }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $category->sort_order }}</td>
                            <td class="px-5 py-3">
                                <span class="whitespace-nowrap rounded-full px-2.5 py-1 text-xs font-medium {{ $category->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $category->is_active ? __('admin/categories.status.active') : __('admin/categories.status.inactive') }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.categories.edit', $category) }}" title="{{ __('admin/categories.table.edit') }}"
                                       class="rounded-lg bg-blue-50 p-2 text-blue-600 transition hover:bg-blue-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                          onsubmit="return confirmSubmit(event, '{{ __('admin/categories.confirm.delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="{{ __('admin/categories.table.delete') }}"
                                                class="rounded-lg bg-red-50 p-2 text-red-500 transition hover:bg-red-100">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">{{ __('admin/categories.empty_state') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-admin-layout>
