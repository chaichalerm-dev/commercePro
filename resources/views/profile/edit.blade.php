<x-storefront-layout :title="__('profile.title')">
    <div class="mx-auto max-w-2xl px-4 py-6 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[__('profile.breadcrumb') => null]" />
        <h1 class="mt-4 text-2xl font-bold text-gray-900">{{ __('profile.title') }}</h1>

        <div class="mt-6 space-y-6">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm sm:p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-storefront-layout>
