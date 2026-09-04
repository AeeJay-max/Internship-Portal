<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    protected array $supported = ['en', 'ru', 'hy'];

    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, $this->supported)) {
            session(['locale' => $locale]);
        }

        return redirect()->back()->withHeaders([
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }
}
