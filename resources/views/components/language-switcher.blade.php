@props(['align' => 'right'])

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button type="button" @click="open = !open"
            class="flex items-center gap-1.5 rounded-full px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m-15.432-9.671A8.959 8.959 0 003 12c0 .778.099 1.533.284 2.253m0 0A17.919 17.919 0 0012 16.5c3.162 0 6.133-.815 8.716-2.247"/></svg>
        <span class="hidden sm:block">{{ config('app.available_locales')[app()->getLocale()] }}</span>
    </button>

    <div x-show="open" x-transition.opacity x-cloak
         class="absolute {{ $align === 'left' ? 'left-0' : 'right-0' }} z-50 mt-2 w-32 overflow-hidden rounded-xl border border-gray-100 bg-white py-1 shadow-lg">
        @foreach (config('app.available_locales') as $code => $label)
            <a href="{{ route('locale.switch', $code) }}"
               class="flex items-center justify-between px-4 py-2 text-sm {{ app()->getLocale() === $code ? 'font-semibold text-primary-600' : 'text-gray-700 hover:bg-gray-50' }}">
                {{ $label }}
                @if (app()->getLocale() === $code)
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                @endif
            </a>
        @endforeach
    </div>
</div>
