<div x-data
     x-show="$store.confirmDialog.show"
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4"
     role="alertdialog" aria-modal="true">
    <div class="absolute inset-0 bg-gray-900/50"
         x-show="$store.confirmDialog.show"
         x-transition.opacity
         @click="$store.confirmDialog.cancel()"></div>

    <div class="relative w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl"
         x-show="$store.confirmDialog.show"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @keydown.escape.window="$store.confirmDialog.cancel()">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
        </div>

        <p class="mt-4 text-base font-medium text-gray-900" x-text="$store.confirmDialog.message"></p>

        <div class="mt-6 flex gap-3">
            <button type="button" @click="$store.confirmDialog.cancel()"
                    class="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">
                {{ __('Cancel') }}
            </button>
            <button type="button" @click="$store.confirmDialog.confirm()"
                    class="flex-1 rounded-xl bg-red-500 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-red-600">
                {{ __('Confirm') }}
            </button>
        </div>
    </div>
</div>
