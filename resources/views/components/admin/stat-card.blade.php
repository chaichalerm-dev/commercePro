@props(['label', 'value', 'icon', 'color' => 'primary'])

@php
    $colors = [
        'primary' => 'bg-primary-50 text-primary-500',
        'emerald' => 'bg-emerald-50 text-emerald-500',
        'blue' => 'bg-blue-50 text-blue-500',
        'violet' => 'bg-violet-50 text-violet-500',
        'amber' => 'bg-amber-50 text-amber-500',
    ];
@endphp

<div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $colors[$color] }}">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
    </span>
    <div class="min-w-0">
        <p class="truncate text-sm text-gray-500">{{ $label }}</p>
        <p class="mt-0.5 truncate text-2xl font-bold text-gray-900">{{ $value }}</p>
    </div>
</div>
