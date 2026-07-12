@php
    $contactEmail = \App\Models\Setting::get('contact_email');
    $contactPhone = \App\Models\Setting::get('contact_phone');
    $socials = [
        'Facebook' => [
            'url' => \App\Models\Setting::get('social_facebook'),
            'icon' => 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z',
        ],
        'Instagram' => [
            'url' => \App\Models\Setting::get('social_instagram'),
            'icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.98-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z',
        ],
        'LINE' => [
            'url' => \App\Models\Setting::get('social_line'),
            'icon' => 'M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V7.812c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V7.812c0-.269.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V7.812c0-.345.282-.63.63-.63.345 0 .63.285.63.63v5.067zm-5.741.629c-.346 0-.626-.285-.626-.629V7.812c0-.345.28-.63.626-.63.348 0 .63.285.63.63v5.067c0 .344-.282.629-.63.629zm-2.466 0H4.917c-.345 0-.63-.285-.63-.629V7.812c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.438h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.588 12 .588S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C22.943 14.943 24 12.797 24 10.314',
        ],
        'YouTube' => [
            'url' => \App\Models\Setting::get('social_youtube'),
            'icon' => 'M23.498 6.186a2.999 2.999 0 00-2.112-2.124C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.386.517A2.999 2.999 0 00.502 6.186 31.26 31.26 0 000 12a31.26 31.26 0 00.502 5.814 2.999 2.999 0 002.112 2.124c1.881.517 9.386.517 9.386.517s7.505 0 9.386-.517a2.999 2.999 0 002.112-2.124A31.26 31.26 0 0024 12a31.26 31.26 0 00-.502-5.814zM9.75 15.568V8.432L15.818 12l-6.068 3.568z',
        ],
    ];
@endphp

<footer class="mt-16 bg-gray-900 text-gray-300">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-12 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-5 lg:gap-10">
            <div class="text-center lg:col-span-2 lg:text-left">
                <p class="text-2xl font-bold tracking-tight">
                    <span class="text-white">SHOP</span><span class="text-primary-500">SMART</span>
                </p>
                <p class="mx-auto mt-3 max-w-sm text-sm leading-relaxed text-gray-400 lg:mx-0">{{ $siteTagline }}</p>
                <div class="mt-5 flex justify-center gap-3 lg:justify-start">
                    @foreach ($socials as $name => $social)
                        @if ($social['url'])
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener"
                               class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-800 text-gray-300 transition hover:bg-primary-500 hover:text-white"
                               title="{{ $name }}" aria-label="{{ $name }}">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="{{ $social['icon'] }}"/></svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-2 gap-x-6 gap-y-8 border-t border-gray-800 pt-8 sm:grid-cols-3 lg:col-span-3 lg:border-0 lg:pt-0">
                <div>
                    <h3 class="text-sm font-semibold text-white">{{ __('nav.footer.customer_service') }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-gray-400">
                        <li><a href="{{ route('pages.contact') }}" class="hover:text-primary-400">{{ __('nav.footer.how_to_order') }}</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="hover:text-primary-400">{{ __('nav.footer.payment') }}</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="hover:text-primary-400">{{ __('nav.footer.shipping') }}</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="hover:text-primary-400">{{ __('nav.footer.returns') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-white">{{ __('nav.footer.company_info') }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-gray-400">
                        <li><a href="{{ route('pages.about') }}" class="hover:text-primary-400">{{ __('nav.footer.about_us') }}</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="hover:text-primary-400">{{ __('nav.footer.contact_us') }}</a></li>
                        <li><a href="{{ route('pages.about') }}" class="hover:text-primary-400">{{ __('nav.footer.careers') }}</a></li>
                        <li><a href="{{ route('pages.about') }}" class="hover:text-primary-400">{{ __('nav.footer.privacy_policy') }}</a></li>
                    </ul>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <h3 class="text-sm font-semibold text-white">{{ __('nav.footer.contact_heading') }}</h3>
                    <ul class="mt-4 space-y-2.5 text-sm text-gray-400">
                        @if ($contactPhone)<li>{{ __('nav.footer.phone', ['phone' => $contactPhone]) }}</li>@endif
                        @if ($contactEmail)<li>{{ __('nav.footer.email', ['email' => $contactEmail]) }}</li>@endif
                        <li class="pt-2">
                            <span class="text-xs text-gray-500">{{ __('nav.footer.demo_line1') }}<br>{{ __('nav.footer.demo_line2') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-800">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 text-xs text-gray-500 sm:flex-row sm:px-6 lg:px-8">
            <p>{{ __('nav.footer.copyright', ['year' => now()->year, 'site' => $siteName]) }}</p>
            <div class="flex flex-wrap justify-center gap-2">
                @foreach (['VISA', 'MasterCard', 'JCB', 'PromptPay'] as $payment)
                    <span class="rounded bg-gray-800 px-2 py-1 font-semibold text-gray-400">{{ $payment }}</span>
                @endforeach
            </div>
        </div>
    </div>
</footer>
