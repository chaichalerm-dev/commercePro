@php
    $contactEmail = \App\Models\Setting::get('contact_email');
    $contactPhone = \App\Models\Setting::get('contact_phone');
    $socials = [
        'Facebook' => \App\Models\Setting::get('social_facebook'),
        'Instagram' => \App\Models\Setting::get('social_instagram'),
        'LINE' => \App\Models\Setting::get('social_line'),
        'YouTube' => \App\Models\Setting::get('social_youtube'),
    ];
@endphp

<footer class="mt-16 bg-gray-900 text-gray-300">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:grid-cols-2 sm:px-6 lg:grid-cols-5 lg:px-8">
        <div class="lg:col-span-2">
            <p class="text-2xl font-bold tracking-tight">
                <span class="text-white">SHOP</span><span class="text-primary-500">SMART</span>
            </p>
            <p class="mt-3 max-w-sm text-sm leading-relaxed text-gray-400">{{ $siteTagline }}</p>
            <div class="mt-5 flex gap-3">
                @foreach ($socials as $name => $url)
                    @if ($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener"
                           class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-800 text-xs font-semibold text-gray-300 transition hover:bg-primary-500 hover:text-white"
                           title="{{ $name }}">{{ mb_substr($name, 0, 2) }}</a>
                    @endif
                @endforeach
            </div>
        </div>

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

        <div>
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

    <div class="border-t border-gray-800">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 text-xs text-gray-500 sm:flex-row sm:px-6 lg:px-8">
            <p>{{ __('nav.footer.copyright', ['year' => now()->year, 'site' => $siteName]) }}</p>
            <div class="flex gap-2">
                @foreach (['VISA', 'MasterCard', 'JCB', 'PromptPay'] as $payment)
                    <span class="rounded bg-gray-800 px-2 py-1 font-semibold text-gray-400">{{ $payment }}</span>
                @endforeach
            </div>
        </div>
    </div>
</footer>
