<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Switch the application locale.
     */
    public function switch(Request $request, string $locale)
    {
        if (! in_array($locale, ['en', 'sw'])) {
            $locale = 'en';
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return back()->with('success', __('alert.success') . ' — ' . __('app.subtitle'));
    }
}