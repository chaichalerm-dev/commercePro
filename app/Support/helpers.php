<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Global Helper Functions
|--------------------------------------------------------------------------
| Registered via composer.json "autoload.files". Keep this file small:
| only cross-cutting formatting helpers belong here — business logic
| lives in app/Services.
*/

if (! function_exists('money')) {
    /**
     * Format an amount as Thai Baht for display, e.g. money(1990) => "฿1,990".
     */
    function money(float|int $amount, bool $withSymbol = true): string
    {
        $formatted = number_format((float) $amount, fmod((float) $amount, 1.0) === 0.0 ? 0 : 2);

        return $withSymbol ? '฿'.$formatted : $formatted;
    }
}
