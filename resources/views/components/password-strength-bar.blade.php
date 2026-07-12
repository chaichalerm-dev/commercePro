{{-- Expects to render inside an x-data="passwordStrength" scope (see
     resources/js/app.js) that exposes: password, score, label, barColor,
     textColor. --}}
<div class="mt-2" x-show="password.length > 0" x-cloak>
    <div class="flex gap-1">
        <template x-for="i in 4" :key="i">
            <span class="h-1.5 flex-1 rounded-full transition-colors duration-200" :class="i <= score ? barColor : 'bg-gray-200'"></span>
        </template>
    </div>
    <p class="mt-1 text-xs font-medium" :class="textColor" x-text="label"></p>
</div>
