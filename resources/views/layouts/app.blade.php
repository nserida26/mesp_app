<!DOCTYPE html>
<html lang="{{ $lang ?? app()->getLocale() }}" dir="{{ $dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'MESP'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans text-gray-900">
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <a href="{{ route('dashboard') }}" class="text-lg font-bold text-primary">@lang('lang.brand') Admin</a>
            <nav class="flex items-center gap-4 text-sm">
                <a class="text-gray-600 hover:text-primary" href="{{ route('public.home') }}">@lang('lang.nav.public_space')</a>
                <a class="{{ app()->getLocale() === 'fr' ? 'font-semibold text-primary' : 'text-gray-600 hover:text-primary' }}" href="{{ route('lang.switch', 'fr') }}">@lang('lang.fr')</a>
                <a class="{{ app()->getLocale() === 'ar' ? 'font-semibold text-primary' : 'text-gray-600 hover:text-primary' }}" href="{{ route('lang.switch', 'ar') }}">@lang('lang.ar')</a>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-600 hover:text-primary" type="submit">@lang('lang.nav.logout')</button>
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6">
        @if (session('success'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-800">{{ session('error') }}</div>
        @endif
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
