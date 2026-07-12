<x-guest-layout>
    <h1 class="text-center text-lg font-bold text-gray-900">{{ __('auth_pages.register.title') }}</h1>
    <p class="mt-1 text-center text-sm text-gray-400">{{ __('auth_pages.register.subtitle') }}</p>

    <form method="POST" action="{{ route('register') }}" class="mt-5">
        @csrf

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.first_name') }}</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus autocomplete="given-name"
                       placeholder="{{ __('auth_pages.register.first_name_placeholder') }}"
                       class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.last_name') }}</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="family-name"
                       placeholder="{{ __('auth_pages.register.last_name_placeholder') }}"
                       class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   placeholder="{{ __('auth_pages.register.email_placeholder') }}"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4" x-data="{ password: '', show: false }">
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.password') }}</label>
            <div class="relative mt-1.5">
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                       x-model="password"
                       placeholder="{{ __('auth_pages.register.password_placeholder') }}"
                       class="block w-full rounded-xl border-gray-200 pr-10 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="{{ __('auth_pages.login.show_password') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
            <x-password-strength-bar password="password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4" x-data="{ show: false }">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('auth_pages.register.password_confirmation') }}</label>
            <div class="relative mt-1.5">
                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                       placeholder="{{ __('auth_pages.register.password_confirmation_placeholder') }}"
                       class="block w-full rounded-xl border-gray-200 pr-10 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="{{ __('auth_pages.login.show_password') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
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
