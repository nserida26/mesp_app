<?php

/**
 * mcamara/laravel-localization configuration
 * Publish with: php artisan vendor:publish --provider="Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider"
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    | Add the locales your application supports. 'name' is the native name,
    | 'script' is the writing system, 'dir' is text direction.
    */
    'supportedLocales' => [
        'fr' => [
            'name'       => 'French',
            'script'     => 'Latn',
            'native'     => 'Français',
            'regional'   => 'fr_FR',
            'dir'        => 'ltr',
        ],
        'ar' => [
            'name'       => 'Arabic',
            'script'     => 'Arab',
            'native'     => 'العربية',
            'regional'   => 'ar_MR',
            'dir'        => 'rtl',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    */
    'useAcceptLanguageHeader' => false,

    'hideDefaultLocaleInURL' => true,       // /fr/... stays as /...

    'localesOrder' => ['fr', 'ar'],

    /*
    |--------------------------------------------------------------------------
    | URL handling
    |--------------------------------------------------------------------------
    */
    'urlsIgnored' => [
        '/lang',       // our custom session-based switcher
        '/api',
        '/sanctum',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locale mapping (optional aliases)
    |--------------------------------------------------------------------------
    */
    'localeDetectionIlluminateLocale' => false,

    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    'routeNameSuffix' => '.',   // e.g. home.fr / home.ar

];
