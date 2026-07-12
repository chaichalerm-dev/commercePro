<x-guest-layout>
    <h1 class="text-center text-lg font-bold text-gray-900">{{ __('auth_pages.register.title') }}</h1>
    <p class="mt-1 text-center text-sm text-gray-400">{{ __('auth_pages.register.subtitle') }}</p>

    <form method="POST" action="{{ route('register') }}" class="mt-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.name') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   placeholder="{{ __('auth_pages.register.name_placeholder') }}"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   placeholder="{{ __('auth_pages.register.email_placeholder') }}"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4" x-data="passwordStrength">
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   x-model="password"
                   placeholder="{{ __('auth_pages.register.password_placeholder') }}"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-password-strength-bar />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.password_confirmation') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   placeholder="{{ __('auth_pages.register.password_confirmation_placeholder') }}"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
                class="mt-6 w-full rounded-xl bg-primary-500 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600">
            {{ __('auth_pages.register.submit') }}
        </button>

        <p class="mt-4 text-center text-sm text-gray-500">
            {{ __('auth_pages.register.has_account') }}
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:underline">{{ __('auth_pages.register.login_cta') }}</a>
        </p>
    </form>
</x-guest-layout>
