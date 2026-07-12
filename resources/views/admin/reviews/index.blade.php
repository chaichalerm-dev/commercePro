<x-admin-layout :title="__('admin/reviews.title')">
    <div class="flex items-center gap-2">
        @foreach (['' => __('admin/reviews.filters.all'), 'approved' => __('admin/reviews.filters.approved'), 'pending' => __('admin/reviews.filters.pending')] as $value => $label)
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
                        <span class="whitespace-nowrap rounded-full px-2 py-0.5 text-xs font-medium {{ $review->is_approved ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $review->is_approved ? __('admin/reviews.status.shown') : __('admin/reviews.status.pending') }}
                        </span>
                    </div>
                    <p class="mt-1 text-xs text-primary-600">{{ __('admin/reviews.product_label', ['name' => $review->product->name]) }}</p>
                    @if ($review->comment)
                        <p class="mt-2 text-sm text-gray-600">{{ $review->comment }}</p>
                    @endif
                </div>
                <div class="flex gap-1.5">
                    <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}">
                        @csrf @method('PATCH')
                        <button type="submit" title="{{ $review->is_approved ? __('admin/reviews.actions.hide') : __('admin/reviews.actions.approve') }}"
                                class="rounded-lg p-2 transition {{ $review->is_approved ? 'bg-amber-50 text-amber-600 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' }}">
                            @if ($review->is_approved)
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                            @else
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirmSubmit(event, '{{ __('admin/reviews.confirm.delete') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" title="{{ __('admin/reviews.actions.delete') }}"
                                class="rounded-lg bg-red-50 p-2 text-red-500 transition hover:bg-red-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-200 bg-white py-12 text-center text-gray-400">{{ __('admin/reviews.empty_state') }}</div>
        @endforelse
    </div>

    <div class="mt-4">{{ $reviews->links() }}</div>
</x-admin-layout>
