<x-admin-layout title="จัดการสินค้า">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.index') }}"
               class="rounded-xl px-4 py-2 text-sm font-medium {{ ! $showTrash ? 'bg-primary-500 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                สินค้าทั้งหมด
            </a>
            <a href="{{ route('admin.products.index', ['view' => 'trash']) }}"
               class="rounded-xl px-4 py-2 text-sm font-medium {{ $showTrash ? 'bg-primary-500 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                ถังขยะ @if($trashCount) <span class="ml-1 rounded-full bg-red-100 px-1.5 text-xs font-bold text-red-500">{{ $trashCount }}</span> @endif
            </a>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="flex items-center gap-2 rounded-xl bg-primary-500 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-primary-600">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            เพิ่มสินค้า
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mt-4 flex flex-wrap items-center gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
        @if ($showTrash)<input type="hidden" name="view" value="trash">@endif
        <input type="search" name="q" value="{{ request('q') }}" placeholder="ค้นหาชื่อสินค้า หรือ SKU..."
               class="w-56 rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
        <select name="category" class="rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            <option value="">ทุกหมวดหมู่</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <select name="status" class="rounded-xl border-gray-200 text-sm focus:border-primary-400 focus:ring-primary-400">
            <option value="">ทุกสถานะ</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">กรอง</button>
        @if (request()->hasAny(['q', 'category', 'status']))
            <a href="{{ route('admin.products.index', $showTrash ? ['view' => 'trash'] : []) }}" class="text-sm text-gray-400 hover:text-gray-600">ล้างตัวกรอง</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-5 py-3 font-medium">สินค้า</th>
                        <th class="px-5 py-3 font-medium">หมวดหมู่</th>
                        <th class="px-5 py-3 font-medium">ราคา</th>
                        <th class="px-5 py-3 font-medium">สต็อก</th>
                        <th class="px-5 py-3 font-medium">สถานะ</th>
                        <th class="px-5 py-3 font-medium">แนะนำ</th>
                        <th class="px-5 py-3 text-right font-medium">จัดการ</th>
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
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $product->status->color() }}">{{ $product->status->label() }}</span>
                            </td>
                            <td class="px-5 py-3">
                                @unless ($showTrash)
                                    <form method="POST" action="{{ route('admin.products.toggle-featured', $product) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" title="สลับสินค้าแนะนำ"
                                                class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $product->featured ? 'bg-primary-500' : 'bg-gray-200' }}">
                                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition {{ $product->featured ? 'translate-x-4.5 ml-0.5' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>
                                @endunless
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if ($showTrash)
                                        <form method="POST" action="{{ route('admin.products.restore', $product->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-600 transition hover:bg-emerald-100">กู้คืน</button>
                                        </form>
                                    @else
                                        <a href="{{ route('products.show', $product->slug) }}" target="_blank" title="ดูหน้าร้าน"
                                           class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 transition hover:bg-blue-100">แก้ไข</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                              onsubmit="return confirm('ย้ายสินค้านี้ไปถังขยะ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-100">ลบ</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">{{ $showTrash ? 'ถังขยะว่างเปล่า' : 'ไม่พบสินค้า' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-gray-50 px-5 py-4">
            {{ $products->links() }}
        </div>
    </div>
</x-admin-layout>
