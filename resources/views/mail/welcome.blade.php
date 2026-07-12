<x-mail::message>
# {{ __('mail.welcome.heading') }}

{{ __('mail.welcome.greeting', ['name' => $user->name]) }}

{{ __('mail.welcome.intro') }}

- {{ __('mail.welcome.perk_shop') }}
- {{ __('mail.welcome.perk_wishlist') }}
- {{ __('mail.welcome.perk_track') }}

{{ __('mail.welcome.promo') }}

<x-mail::button :url="route('products.index')">
{{ __('mail.welcome.cta') }}
</x-mail::button>

{{ __('mail.welcome.thanks') }}<br>
{{ __('mail.welcome.team', ['app' => config('app.name')]) }}
</x-mail::message>
