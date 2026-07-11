@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'text-sm text-gray-500']) }}>
    <ol class="flex flex-wrap items-center gap-1.5">
        <li><a href="{{ route('home') }}" class="hover:text-primary-600">หน้าแรก</a></li>
        @foreach ($items as $label => $url)
            <li class="flex items-center gap-1.5">
                <svg class="h-3.5 w-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                @if ($url)
                    <a href="{{ $url }}" class="hover:text-primary-600">{{ $label }}</a>
                @else
                    <span class="font-medium text-gray-800">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
