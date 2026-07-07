<!DOCTYPE html>
<html lang="{{ $lang ?? app()->getLocale() }}" dir="{{ $dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr') }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@lang('lang.public.protected_footer')">
    <title>@yield('title', __('lang.portal_short')) - @lang('lang.ministry_name')</title>
    <link rel="icon" href="/assets/logo_rim.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Tajawal:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>

<body class="bg-[#F5F7FA] font-sans text-[#1A1A2E] antialiased">
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="grid min-h-20 grid-cols-1 items-center gap-3 py-4 text-center md:grid-cols-[1fr_auto_1fr] md:text-left">
                <div class="flex items-center justify-center gap-3 md:justify-start">
                    <img src="/assets/logo_rim.png" height="45" width="45" alt="Logo Mauritanie"
                        class="h-12 w-12 object-contain">
                    <img src="/assets/slogan_rim.png" height="45" width="100" alt="Slogan"
                        class="h-12 w-auto object-contain">
                </div>

                <div class="text-center">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">@lang('lang.ministry_name')</p>
                    <h1 class="text-base font-black text-green-700 md:text-lg">@lang('lang.portal_name')</h1>
                </div>

                <div class="flex items-center justify-center gap-2 md:justify-end">

                    <a href="{{ route('lang.switch', 'fr') }}"
                        class="rounded-xl border px-3 py-1.5 text-sm font-semibold transition {{ app()->getLocale() === 'fr' ? 'border-green-700 bg-green-700 text-white shadow-sm' : 'border-green-100 text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                        @lang('lang.fr')
                    </a>
                    <a href="{{ route('lang.switch', 'ar') }}"
                        class="rounded-xl border px-3 py-1.5 text-sm font-semibold transition {{ app()->getLocale() === 'ar' ? 'border-green-700 bg-green-700 text-white shadow-sm' : 'border-green-100 text-gray-600 hover:bg-green-50 hover:text-green-700' }}">
                        @lang('lang.ar')
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex min-h-14 flex-wrap items-center justify-center gap-2 border-t border-gray-100 py-2">
                <a href="{{ route('public.home') }}"
                    class="{{ request()->routeIs('public.home') ? 'bg-green-700 text-white shadow-sm' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }} inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold transition">
                    @lang('lang.nav.home')
                </a>
                <a href="{{ route('public.verify') }}"
                    class="{{ request()->routeIs('public.verify*') ? 'bg-green-700 text-white shadow-sm' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }} inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold transition">
                    @lang('lang.nav.verify')
                </a>
                <a href="{{ route('public.institutions') }}"
                    class="{{ request()->routeIs('public.institutions*') ? 'bg-green-700 text-white shadow-sm' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }} inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold transition">
                    @lang('lang.nav.institutions')
                </a>
                <a href="{{ route('public.filieres') }}"
                    class="{{ request()->routeIs('public.filieres*') ? 'bg-green-700 text-white shadow-sm' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }} inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold transition">
                    @lang('lang.nav.formations')
                </a>
                <a href="{{ route('public.etudiants') }}"
                    class="{{ request()->routeIs('public.etudiants*') ? 'bg-green-700 text-white shadow-sm' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }} inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold transition">
                    @lang('lang.nav.students')
                </a>
                <a href="{{ route('public.enseignants') }}"
                    class="{{ request()->routeIs('public.enseignants*') ? 'bg-green-700 text-white shadow-sm' : 'text-gray-600 hover:bg-green-50 hover:text-green-700' }} inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold transition">
                    @lang('lang.teachers_public.title')
                </a>

            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-12 border-t border-gray-100 bg-white shadow-inner">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} @lang('lang.ministry_name'). @lang('lang.public.copyright')</p>
                <p class="mt-2">
                    <i class="fas fa-shield-alt text-green-600 mr-1"></i>
                    @lang('lang.public.protected_footer')
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
