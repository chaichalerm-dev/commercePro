<section
    x-data="{
        current_password: '', password: '', password_confirmation: '',
        showCurrent: false, showNew: false, showConfirm: false,
        get dirty() {
            return this.current_password !== '' && this.password !== '' && this.password_confirmation !== '';
        },
    }"
>
    <header>
        <h2 class="text-lg font-semibold text-gray-900">{{ __('profile.password.heading') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ __('profile.password.subtitle') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-medium text-gray-700">{{ __('profile.password.current') }}</label>
            <div class="relative mt-1.5">
                <input id="update_password_current_password" name="current_password" :type="showCurrent ? 'text' : 'password'"
                       x-model="current_password" autocomplete="current-password"
                       class="block w-full rounded-xl border-gray-200 pr-10 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="{{ __('auth_pages.login.show_password') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-medium text-gray-700">{{ __('profile.password.new') }}</label>
            <div class="relative mt-1.5">
                <input id="update_password_password" name="password" :type="showNew ? 'text' : 'password'"
                       x-model="password" autocomplete="new-password"
                       class="block w-full rounded-xl border-gray-200 pr-10 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="{{ __('auth_pages.login.show_password') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
            <x-password-strength-bar password="password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('profile.password.confirm') }}</label>
            <div class="relative mt-1.5">
                <input id="update_password_password_confirmation" name="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                       x-model="password_confirmation" autocomplete="new-password"
                       class="block w-full rounded-xl border-gray-200 pr-10 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600" aria-label="{{ __('auth_pages.login.show_password') }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" :disabled="!dirty"
                    class="rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600 disabled:cursor-not-allowed disabled:bg-gray-200 disabled:text-gray-400 disabled:shadow-none">
                {{ __('profile.password.save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-500">
                    {{ __('profile.password.saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
