<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Persist the chosen UI locale in session and return to the previous page.
     */
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        if (array_key_exists($locale, config('app.available_locales'))) {
            $request->session()->put('locale', $locale);
        }

        return redirect()->to(url()->previous(fallback: route('home')));
    }
}
