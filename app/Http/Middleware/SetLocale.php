<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/**
 * SetLocale Middleware
 * Works alongside mcamara/laravel-localization.
 * Falls back to session locale or config default.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supported = array_keys(
            config('laravellocalization.supportedLocales', ['fr' => [], 'ar' => []])
        );

        // 1. mcamara sets locale via URL prefix automatically
        // 2. Fall back to session
        if (Session::has('locale') && in_array(Session::get('locale'), $supported)) {
            App::setLocale(Session::get('locale'));
        }

        // 3. Set document direction in view
        view()->share('dir', in_array(App::getLocale(), ['ar']) ? 'rtl' : 'ltr');
        view()->share('lang', App::getLocale());

        return $next($request);
    }
}
