<?php

declare(strict_types=1);

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('storefront.pages.about');
    }

    public function contact(): View
    {
        return view('storefront.pages.contact');
    }
}
