<x-admin-layout title="รีวิวสินค้า">
    <div class="flex items-center gap-2">
        @foreach (['' => 'ทั้งหมด', 'approved' => 'อนุมัติแล้ว', 'pending' => 'รออนุมัติ'] as $value => $label)
            <a href="{{ route('admin.reviews.index', $value ? ['status' => $value] : []) }}"
               class="rounded-xl px-4 py-2 text-sm font-medium {{ request('status', '') === $value ? 'bg-primary-500 text-white shadow' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="mt-4 space-y-3">
        @forelse ($reviews as $review)
            <div class="flex flex-wrap items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-medium text-gray-800">{{ $review->user->name }}</span>
                        <x-star-rating :rating="$review->rating" />
                        <span class="text-xs text-gray-400">{{ $review->created_at->format('d/m/Y') }}</span>
                        <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $review->is_approved ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $review->is_approved ? 'แสดงบนเว็บ' : 'รออนุมัติ' }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">สินค้า: {{ $review->product->name }}</p>
                    @if ($review->comment)
                        <p class="mt-2 text-sm text-gray-600">{{ $review->comment }}</p>
                    @endif
                </div>
                <div class="flex gap-1.5">
                    <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-medium transition {{ $review->is_approved ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                            {{ $review->is_approved ? 'ซ่อน' : 'อนุมัติ' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('ลบรีวิวนี้?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-500 transition hover:bg-red-100">ลบ</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-200 bg-white py-12 text-center text-gray-400">ไม่พบรีวิว</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
</x-admin-layout>
