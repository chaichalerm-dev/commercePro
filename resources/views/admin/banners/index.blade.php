<x-admin-layout title="แบนเนอร์">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">ทั้งหมด {{ $banners->total() }} แบนเนอร์</p>
        <a href="{{ route('admin.banners.create') }}"
           class="flex items-center gap-2 rounded-xl bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            เพิ่มแบนเนอร์
        </a>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse ($banners as $banner)
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" loading="lazy" class="aspect-[8/3] w-full object-cover">
                <div class="p-4">
                    <div class="flex items-center gap-2">
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">{{ $banner->position->label() }}</span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $banner->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                            {{ $banner->is_active ? 'แสดงอยู่' : 'ซ่อน' }}
                        </span>
                        <span class="ml-auto text-xs text-gray-400">ลำดับ {{ $banner->sort_order }}</span>
                    </div>
                    <p class="mt-2 truncate font-medium text-gray-800">{{ $banner->title }}</p>
                    @if ($banner->subtitle)
                        <p class="truncate text-xs text-gray-400">{{ $banner->subtitle }}</p>
                    @endif
                    <div class="mt-3 flex gap-1.5">
                        <a href="{{ route('admin.banners.edit', $banner) }}" class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100">แก้ไข</a>
                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('ลบแบนเนอร์นี้?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-100">ลบ</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-gray-200 bg-white py-12 text-center text-gray-400">ยังไม่มีแบนเนอร์</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $banners->links() }}</div>
</x-admin-layout>
