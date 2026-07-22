<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ \App\Models\Setting::url('favicon') ?? asset('favicon.png') }}">

        <title>{{ config('app.name', 'ShopSmart') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|noto-sans-thai:400,500,600,700&display=swap" rel="stylesheet" />

        @include('partials.password-strength-labels')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen flex-col items-center bg-gray-50 pt-6 sm:justify-center sm:pt-0">
            <div class="absolute right-4 top-4">
                <x-language-switcher />
            </div>

            <a href="{{ route('home') }}">
                @if ($logoUrl = \App\Models\Setting::url('logo'))
                    <img src="{{ $logoUrl }}" alt="{{ config('app.name', 'ShopSmart') }}" class="h-10 w-auto">
                @else
                    <span class="text-3xl font-bold tracking-tight"><span class="text-gray-900">SHOP</span><span class="text-primary-500">SMART</span></span>
                @endif
            </a>

            <div class="mt-6 w-full overflow-hidden bg-white px-6 py-6 shadow-md sm:max-w-md sm:rounded-2xl">
                {{ $slot }}
            </div>

            <p class="mt-6 text-xs text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-primary-600">← {{ __('common.back_to_store') }}</a>
            </p>
        </div>

        <x-confirm-dialog />
    </body>
</html>
