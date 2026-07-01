<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'MESP')) - Admin</title>
    <link rel="icon" href="/assets/logo_rim.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Tajawal:wght@400;500;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>

<body class="min-h-screen bg-[#F5F7FA] font-sans text-[#1A1A2E] antialiased">

    {{-- ── Header ────────────────────────────────────────────────────────── --}}
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
            <div class="flex min-h-20 items-center justify-between gap-4">

                {{-- Left: logos + branding --}}
                <div class="flex min-w-0 items-center gap-3">
                    <img src="/assets/logo_rim.png" height="45" width="45" alt="Logo" class="h-12 w-12 shrink-0 object-contain">
                    <img src="/assets/slogan_rim.png" height="45" width="100" alt="Slogan"
                        class="hidden h-12 w-auto shrink-0 object-contain sm:block">
                    <div class="hidden min-w-0 border-s border-gray-200 ps-3 lg:block">
                        <p class="truncate text-[10px] font-semibold uppercase tracking-wider text-gray-400">
                            @lang('lang.ministry_name')
                        </p>
                        <p class="whitespace-nowrap text-sm font-black text-primary">
                            @lang('lang.brand') - @lang('lang.admin.admin_space')
                        </p>
                    </div>
                </div>

                {{-- Right: nav + lang switcher --}}
                <nav class="flex items-center gap-2 text-sm">
                    <a href="{{ route('public.home') }}"
                        class="hidden items-center gap-2 rounded-xl border border-green-100 px-3 py-2 text-gray-600 transition hover:bg-green-50 hover:text-primary sm:flex">
                        <i class="fas fa-globe text-xs"></i>
                        @lang('lang.nav.public_space')
                    </a>

                    <div class="h-5 w-px bg-gray-200"></div>

                    <a href="{{ route('lang.switch', 'fr') }}"
                        class="rounded-xl border px-3 py-1.5 text-xs font-semibold transition
                              {{ app()->getLocale() === 'fr' ? 'border-primary bg-primary text-white shadow-sm' : 'border-green-100 text-gray-600 hover:bg-green-50 hover:text-primary' }}">
                        FR
                    </a>
                    <a href="{{ route('lang.switch', 'ar') }}"
                        class="rounded-xl border px-3 py-1.5 text-xs font-semibold transition
                              {{ app()->getLocale() === 'ar' ? 'border-primary bg-primary text-white shadow-sm' : 'border-green-100 text-gray-600 hover:bg-green-50 hover:text-primary' }}">
                        AR
                    </a>

                    @auth
                        <div class="h-5 w-px bg-gray-200"></div>
                        <div class="flex items-center gap-2">
                            <span class="hidden max-w-36 truncate text-xs font-semibold text-gray-600 sm:block">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600">
                                    <i class="fas fa-sign-out-alt me-1"></i>
                                    @lang('lang.nav.logout')
                                </button>
                            </form>
                        </div>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    {{-- ── Main ─────────────────────────────────────────────────────────── --}}
    <main class="min-h-[calc(100vh-5rem)]">
        @if (session('success'))
            <div class="border-b border-green-200 bg-green-50 px-6 py-3 text-sm text-green-800">
                <i class="fas fa-check-circle me-2 text-green-500"></i>{{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="border-b border-red-200 bg-red-50 px-6 py-3 text-sm text-red-800">
                <i class="fas fa-exclamation-circle me-2 text-red-400"></i>{{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
