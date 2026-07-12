@php
    $passwordStrengthLabels = [
        __('common.password_strength.very_weak'),
        __('common.password_strength.weak'),
        __('common.password_strength.fair'),
        __('common.password_strength.good'),
        __('common.password_strength.very_strong'),
    ];
@endphp
<script nonce="{{ $cspNonce }}">
    window.passwordStrengthLabels = @json($passwordStrengthLabels);
</script>
