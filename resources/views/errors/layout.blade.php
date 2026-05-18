<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - {{ config('app.name', 'MESRS') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    <main class="mx-auto flex min-h-screen max-w-3xl flex-col items-center justify-center px-6 py-12 text-center">
        <p class="text-sm font-semibold uppercase tracking-wide text-primary">@yield('code')</p>
        <h1 class="mt-3 text-3xl font-bold text-gray-950 sm:text-4xl">@yield('title')</h1>
        <p class="mt-4 max-w-xl text-base text-gray-600">@yield('message')</p>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('public.home') }}"
                class="rounded-md border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:border-primary hover:text-primary">
                @lang('lang.actions.back')
            </a>
            <a href="{{ route('public.home') }}"
                class="rounded-md bg-primary px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-600">
                @lang('lang.nav.home')
            </a>
            @auth
                <a href="{{ route('dashboard') }}"
                    class="rounded-md bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700">
                    @lang('lang.nav.dashboard')
                </a>
            @endauth
        </div>
    </main>
</body>
</html>
