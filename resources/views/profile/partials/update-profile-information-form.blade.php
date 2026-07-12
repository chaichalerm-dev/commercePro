<section
    x-data="{
        initial: { first_name: @js(old('first_name', $firstName)), last_name: @js(old('last_name', $lastName)), email: @js(old('email', $user->email)) },
        first_name: @js(old('first_name', $firstName)),
        last_name: @js(old('last_name', $lastName)),
        email: @js(old('email', $user->email)),
        get dirty() {
            return this.first_name !== this.initial.first_name
                || this.last_name !== this.initial.last_name
                || this.email !== this.initial.email;
        },
    }"
>
    <header>
        <h2 class="text-lg font-semibold text-gray-900">{{ __('profile.info.heading') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ __('profile.info.subtitle') }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">{{ __('profile.info.first_name') }}</label>
                <input id="first_name" name="first_name" type="text" x-model="first_name" required autofocus autocomplete="given-name"
                       class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">{{ __('profile.info.last_name') }}</label>
                <input id="last_name" name="last_name" type="text" x-model="last_name" required autocomplete="family-name"
                       class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('profile.info.email') }}</label>
            <input id="email" name="email" type="email" x-model="email" required autocomplete="username"
                   class="mt-1.5 block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-primary-400 focus:ring-primary-400">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ __('profile.info.email_unverified') }}
                        <button form="send-verification" class="text-gray-700 underline hover:text-gray-900">
                            {{ __('profile.info.resend_link') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-emerald-600">
                            {{ __('profile.info.link_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" :disabled="!dirty"
                    class="rounded-xl bg-primary-500 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-primary-600 disabled:cursor-not-allowed disabled:bg-gray-200 disabled:text-gray-400 disabled:shadow-none">
                {{ __('profile.info.save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-500">
                    {{ __('profile.info.saved') }}
                </p>
            @endif
        </div>
    </form>
</section>
