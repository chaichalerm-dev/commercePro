@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'canonical' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO --}}
    <title>{{ $title ? "{$title} | {$siteName}" : "{$siteName} — {$siteTagline}" }}</title>
    <meta name="description" content="{{ $description ?? $siteTagline }}">
    <link rel="canonical" href="{{ $canonical ?? url()->current() }}">

    {{-- Open Graph / Twitter Card --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $title ?? $siteName }}">
    <meta property="og:description" content="{{ $description ?? $siteTagline }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $image ?? asset('images/placeholder.svg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? $siteName }}">
    <meta name="twitter:description" content="{{ $description ?? $siteTagline }}">
    <meta name="twitter:image" content="{{ $image ?? asset('images/placeholder.svg') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|noto-sans-thai:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ $head ?? '' }}
</head>
<body class="min-h-screen bg-gray-50 font-sans text-gray-800 antialiased">
    @include('partials.storefront.header')

    {{-- Flash toast --}}
    @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition.opacity.duration.300ms x-cloak
             class="fixed right-4 top-24 z-50 flex max-w-sm items-center gap-3 rounded-xl border p-4 shadow-lg {{ session('success') ? 'border-emerald-100 bg-emerald-50 text-emerald-700' : 'border-red-100 bg-red-50 text-red-600' }}">
            @if (session('success'))
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            @else
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            @endif
            <p class="text-sm font-medium">{{ session('success') ?? session('error') }}</p>
            <button @click="show = false" class="ml-auto opacity-60 hover:opacity-100" aria-label="ปิด">✕</button>
        </div>
    @endif

    <main>
        {{ $slot }}
    </main>

    @include('partials.storefront.footer')
</body>
</html>
