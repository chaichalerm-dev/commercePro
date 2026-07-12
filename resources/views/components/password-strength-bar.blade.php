@props(['password'])

{{-- $password is the *name* of the parent x-data scope's reactive password
     variable (e.g. "password" or "newPassword"), not its value. --}}
<div class="mt-2" x-show="{{ $password }}.length > 0" x-cloak>
    <div class="flex gap-1">
        <template x-for="i in 4" :key="i">
            <span class="h-2 flex-1 rounded-full transition-colors duration-200"
                  :class="i <= passwordStrengthMeta({{ $password }}).filled ? passwordStrengthMeta({{ $password }}).barColor : 'bg-gray-100'"></span>
        </template>
    </div>
    <p class="mt-1.5 text-xs font-semibold" :class="passwordStrengthMeta({{ $password }}).textColor" x-text="passwordStrengthMeta({{ $password }}).label"></p>
</div>
