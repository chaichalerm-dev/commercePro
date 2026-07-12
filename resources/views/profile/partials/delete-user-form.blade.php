<section class="space-y-4">
    <header>
        <h2 class="text-lg font-semibold text-red-600">{{ __('profile.delete.heading') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ __('profile.delete.subtitle') }}</p>
    </header>

    <div class="rounded-xl bg-red-50 p-4">
        <p class="text-sm font-medium text-red-700">{{ __('profile.delete.warning_heading') }}</p>
        <ul class="mt-2 space-y-1 text-sm text-red-600">
            <li class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ __('profile.delete.warning_orders') }}
            </li>
            <li class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ __('profile.delete.warning_addresses') }}
            </li>
            <li class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ __('profile.delete.warning_wishlist') }}
            </li>
            <li class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ __('profile.delete.warning_reviews') }}
            </li>
        </ul>
    </div>

    <button type="button" x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="rounded-xl border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">
        {{ __('profile.delete.trigger') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable maxWidth="sm">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-gray-900">{{ __('profile.delete.modal_heading') }}</h2>
            <p class="mt-1 text-sm text-gray-500">{{ __('profile.delete.modal_subtitle') }}</p>

            <div class="mt-5">
                <label for="password" class="sr-only">{{ __('profile.delete.password_placeholder') }}</label>
                <input id="password" name="password" type="password" placeholder="{{ __('profile.delete.password_placeholder') }}"
                       class="block w-full rounded-xl border-gray-200 text-sm shadow-sm focus:border-red-400 focus:ring-red-400">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                    {{ __('profile.delete.cancel') }}
                </button>
                <button type="submit"
                        class="rounded-xl bg-red-500 px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-red-600">
                    {{ __('profile.delete.confirm') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
