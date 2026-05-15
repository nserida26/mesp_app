<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application locale and redirect back.
     * Compatible with mcamara/laravel-localization.
     */
    public function switch(string $locale)
    {
        $supported = config('laravellocalization.supportedLocales', ['fr' => [], 'ar' => []]);

        if (!array_key_exists($locale, $supported)) {
            abort(400, 'Unsupported locale');
        }

        Session::put('locale', $locale);
        App::setLocale($locale);

        // If using mcamara prefix-based URLs, redirect to the localized home
        return redirect()->back()->withInput();
    }
}
