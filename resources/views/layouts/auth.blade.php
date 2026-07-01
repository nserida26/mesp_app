<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('lang.auth.login')) — @lang('lang.brand')</title>
    <link rel="icon" href="/assets/logo_rim.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">

    <div class="flex min-h-screen">

        {{-- ── Left decorative panel ── --}}
        <div class="relative hidden w-[45%] flex-col justify-between overflow-hidden bg-gradient-to-br from-green-800 via-green-700 to-teal-600 p-10 lg:flex">

            {{-- Background pattern --}}
            <div class="pointer-events-none absolute inset-0 opacity-10"
                 style="background-image:url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");">
            </div>

            {{-- Top logos --}}
            <div class="relative z-10 flex items-center gap-4">
                <img src="/assets/logo_rim.png" alt="Logo" class="h-14 w-14 object-contain drop-shadow-lg">
                <img src="/assets/slogan_rim.png" alt="Slogan" class="h-12 w-auto object-contain opacity-90">
            </div>

            {{-- Center text --}}
            <div class="relative z-10 flex-1 flex flex-col justify-center py-12">
                <div class="mb-4 inline-flex w-fit items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-1.5 text-xs font-semibold text-white/90 backdrop-blur-sm">
                    <i class="fas fa-shield-halved"></i>
                    @lang('lang.public.protected_footer')
                </div>

                <h2 class="mt-4 text-3xl font-black leading-tight text-white">
                    @lang('lang.ministry_name')
                </h2>
                <p class="mt-3 text-base font-semibold text-green-100">
                    @lang('lang.portal_name')
                </p>
                <p class="mt-5 text-sm leading-relaxed text-green-200/80">
                    @lang('lang.public.hero_subtitle')
                </p>

                {{-- Feature list --}}
                <ul class="mt-8 space-y-3 text-sm text-green-100">
                    <li class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20">
                            <i class="fas fa-graduation-cap text-xs"></i>
                        </span>
                        @lang('lang.public.authorized_formations')
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20">
                            <i class="fas fa-university text-xs"></i>
                        </span>
                        @lang('lang.public.accredited_institutions')
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white/20">
                            <i class="fas fa-user-graduate text-xs"></i>
                        </span>
                        @lang('lang.public.registered_students')
                    </li>
                </ul>
            </div>

            {{-- Bottom language switcher --}}
            <div class="relative z-10 flex items-center gap-2">
                <a href="{{ route('lang.switch', 'fr') }}"
                   class="rounded-lg border px-3 py-1.5 text-xs font-semibold transition {{ app()->getLocale() === 'fr' ? 'border-white bg-white text-green-800' : 'border-white/40 text-white/80 hover:border-white hover:text-white' }}">
                    FR
                </a>
                <a href="{{ route('lang.switch', 'ar') }}"
                   class="rounded-lg border px-3 py-1.5 text-xs font-semibold transition {{ app()->getLocale() === 'ar' ? 'border-white bg-white text-green-800' : 'border-white/40 text-white/80 hover:border-white hover:text-white' }}">
                    AR
                </a>
            </div>
        </div>

        {{-- ── Right form panel ── --}}
        <div class="flex flex-1 flex-col justify-between px-6 py-10 sm:px-12 lg:px-16">

            {{-- Mobile header --}}
            <div class="flex items-center justify-between lg:hidden">
                <div class="flex items-center gap-3">
                    <img src="/assets/logo_rim.png" alt="Logo" class="h-10 w-10 object-contain">
                    <span class="text-base font-bold text-green-700">@lang('lang.brand')</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('lang.switch', 'fr') }}"
                       class="rounded-lg border px-2.5 py-1 text-xs font-semibold {{ app()->getLocale() === 'fr' ? 'border-green-700 bg-green-700 text-white' : 'border-gray-300 text-gray-600' }}">FR</a>
                    <a href="{{ route('lang.switch', 'ar') }}"
                       class="rounded-lg border px-2.5 py-1 text-xs font-semibold {{ app()->getLocale() === 'ar' ? 'border-green-700 bg-green-700 text-white' : 'border-gray-300 text-gray-600' }}">AR</a>
                </div>
            </div>

            {{-- Form content --}}
            <div class="mx-auto w-full max-w-sm flex-1 flex flex-col justify-center">
                @yield('content')
            </div>

            {{-- Footer --}}
            <p class="mt-8 text-center text-xs text-gray-400">
                &copy; {{ date('Y') }} @lang('lang.ministry_name')
            </p>
        </div>

    </div>

    @stack('scripts')
</body>
</html>
