<x-admin-layout title="จัดการหมวดหมู่">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">ทั้งหมด {{ $categories->total() }} หมวดหมู่</p>
        <a href="{{ route('admin.categories.create') }}"
           class="flex items-center gap-2 rounded-xl bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            เพิ่มหมวดหมู่
        </a>
    </div>

    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">หมวดหมู่</th>
                        <th class="px-5 py-3 font-medium">Slug</th>
                        <th class="px-5 py-3 font-medium">จำนวนสินค้า</th>
                        <th class="px-5 py-3 font-medium">ลำดับ</th>
                        <th class="px-5 py-3 font-medium">สถานะ</th>
                        <th class="px-5 py-3 text-right font-medium">จัดการ</th>
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
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $category->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $category->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                       class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100">แก้ไข</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                          onsubmit="return confirm('ลบหมวดหมู่นี้?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-100">ลบ</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">ยังไม่มีหมวดหมู่</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">
            {{ $categories->links() }}
        </div>
    </div>
</x-admin-layout>
