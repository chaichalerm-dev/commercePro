<x-admin-layout :title="__('admin/banners.title')">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">{{ __('admin/banners.total_count', ['count' => $bannersByPosition->sum(fn ($group) => $group->count())]) }}</p>
        <a href="{{ route('admin.banners.create') }}"
           class="flex items-center gap-2 rounded-xl bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            {{ __('admin/banners.form.submit_create') }}
        </a>
    </div>

    @foreach ($positions as $position)
        @php $group = $bannersByPosition[$position->value]; @endphp
        <section class="mt-6 first:mt-4">
            <div class="flex items-center gap-2">
                <h2 class="text-base font-semibold text-gray-900">{{ $position->label() }}</h2>
                <span class="whitespace-nowrap rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">{{ $group->count() }}</span>
            </div>

            <div class="mt-3 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($group as $banner)
                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" loading="lazy" class="aspect-[8/3] w-full object-cover">
                        <div class="p-4">
                            <div class="flex items-center gap-2">
                                <span class="whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-medium {{ $banner->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $banner->is_active ? __('admin/banners.status.active') : __('admin/banners.status.hidden') }}
                                </span>
                                <span class="ml-auto text-xs text-gray-400">{{ __('admin/banners.sort_order_label', ['n' => $banner->sort_order + 1]) }}</span>
                            </div>
                            <p class="mt-2 truncate font-medium text-gray-800">{{ $banner->title }}</p>
                            @if ($banner->subtitle)
                                <p class="truncate text-xs text-gray-400">{{ $banner->subtitle }}</p>
                            @endif
                            <div class="mt-3 flex gap-1.5">
                                <a href="{{ route('admin.banners.edit', $banner) }}" title="{{ __('admin/banners.table.edit') }}"
                                   class="rounded-lg bg-blue-50 p-2 text-blue-600 transition hover:bg-blue-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirmSubmit(event, '{{ __('admin/banners.confirm.delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="{{ __('admin/banners.table.delete') }}"
                                            class="rounded-lg bg-red-50 p-2 text-red-500 transition hover:bg-red-100">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-gray-200 bg-white py-10 text-center text-sm text-gray-400">{{ __('admin/banners.empty_state') }}</div>
                @endforelse
            </div>
        </section>
    @endforeach
</x-admin-layout>
