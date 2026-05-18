# 🎓 Laravel UI Style Guide — منصة طلبة التعليم العالي
> Design system and Blade component prompt guide inspired by the Mauritanian Higher Education Student Platform

---

## 🎨 Design Identity

| Property | Value |
|----------|-------|
| **Direction** | RTL (Right-to-Left) for Arabic, LTR for French |
| **Primary Color** | `#1A7A4A` (Deep Green) |
| **Accent Color** | `#2ECC8B` (Teal/Mint Green) |
| **Secondary** | `#4B6EF5` (Royal Blue — for housing/السكن) |
| **Highlight** | `#00C2B2` (Cyan Teal — for transport/النقل) |
| **Background** | `#F5F7FA` (Light Gray) |
| **Card Background** | `#FFFFFF` |
| **Text Primary** | `#1A1A2E` |
| **Text Muted** | `#6B7280` |

---

## 🗂️ Laravel Project Structure

```
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php          ← Main layout (RTL/LTR toggle)
│   ├── components/
│   │   ├── navbar.blade.php
│   │   ├── hero.blade.php
│   │   ├── stat-card.blade.php
│   │   ├── service-card.blade.php
│   │   ├── action-banner.blade.php
│   │   └── btn.blade.php
│   └── dashboard/
│       └── index.blade.php
├── css/
│   └── app.css                    ← Tailwind + custom vars
public/
└── images/
    └── coat-of-arms.png
```

---

## 🖥️ Master Layout — `layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ar' ? 'ar' : 'fr' }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'منصة طلبة التعليم العالي')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-cairo antialiased">

    {{-- Navbar --}}
    <x-navbar />

    {{-- Page Content --}}
    <main>
        @yield('content')
    </main>

</body>
</html>
```

---

## 🧭 Navbar Component — `components/navbar.blade.php`

```blade
<nav class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo / Coat of Arms --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/coat-of-arms.png') }}" alt="Logo" class="h-10 w-10 object-contain">
                <div class="text-right leading-tight hidden sm:block">
                    <p class="text-[10px] text-gray-500 font-medium">الجمهورية الإسلامية الموريتانية</p>
                    <p class="text-[10px] text-gray-400">RÉPUBLIQUE ISLAMIQUE DE MAURITANIE</p>
                    <p class="text-[9px] text-gray-400 italic">Honneur · Fraternité · Justice</p>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="text-xl font-bold text-gray-800 tracking-wide">
                منصة طلبة التعليم العالي
            </h1>

            {{-- Language Toggle + Menu --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('locale.switch', 'fr') }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-green-600 text-green-700 text-sm font-semibold hover:bg-green-50 transition">
                    🌐 Français
                </a>
                <button class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>
```

---

## 🦸 Hero Section — `components/hero.blade.php`

```blade
<section class="relative bg-gradient-to-br from-white via-green-50/30 to-teal-50/20 py-20 px-6 text-center overflow-hidden">

    {{-- Background Decoration --}}
    <div class="absolute inset-0 opacity-5 pointer-events-none"
         style="background-image: radial-gradient(circle at 20% 80%, #1A7A4A 0%, transparent 50%),
                                  radial-gradient(circle at 80% 20%, #2ECC8B 0%, transparent 50%);">
    </div>

    {{-- Badge --}}
    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-green-200 bg-white/80 text-green-700 text-sm font-semibold mb-6 shadow-sm">
        <span>🏛️</span> منصة وطنية
    </div>

    {{-- Heading --}}
    <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight mb-4 max-w-2xl mx-auto">
        مرحبًا بكم في منصة طلبة التعليم العالي
    </h1>

    {{-- Subtitle --}}
    <p class="text-gray-500 text-lg max-w-xl mx-auto mb-8 leading-relaxed">
        بوابتكم للتعليم العالي في موريتانيا. بسّطوا مساركم الأكاديمي، من التسجيل إلى النجاح.
    </p>

    {{-- CTA Button --}}
    <a href="#services"
       class="inline-flex items-center gap-2 px-8 py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-200 transition-all duration-200 text-base">
        الخدمات والوصول السريع
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </a>

</section>
```

---

## 📊 Statistics Section — `components/stat-card.blade.php`

```blade
{{-- Usage: <x-stat-card label="إجمالي الطلاب" value="48634" color="green" /> --}}

@props([
    'label'  => 'إجمالي الطلاب',
    'value'  => '0',
    'color'  => 'green',   // green | teal | blue | purple
])

@php
$colors = [
    'green'  => 'bg-green-700 text-white',
    'teal'   => 'bg-teal-500 text-white',
    'blue'   => 'bg-blue-500 text-white',
    'purple' => 'bg-purple-500 text-white',
];
$cls = $colors[$color] ?? $colors['green'];
@endphp

<div class="rounded-2xl {{ $cls }} px-6 py-5 text-center shadow-md min-w-[140px]">
    <p class="text-3xl font-black tracking-tight">{{ number_format($value) }}</p>
    <p class="text-sm font-medium mt-1 opacity-90">{{ $label }}</p>
</div>
```

**Usage in a view:**
```blade
<section class="py-12 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">الإحصائيات العامة للطلاب</h2>
        <p class="text-center text-gray-400 mb-8">2025–2026</p>

        <div class="bg-gray-50 rounded-2xl border p-6">
            <p class="text-end font-semibold text-gray-700 mb-4">الأرقام الرئيسية</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <x-stat-card label="إجمالي الطلاب" value="48634" color="green" />
                <x-stat-card label="المنحة"         value="20707" color="teal" />
                <x-stat-card label="السكن"          value="1960"  color="blue" />
                <x-stat-card label="النقل"          value="5635"  color="purple" />
            </div>
        </div>
    </div>
</section>
```

---

## 🃏 Service Card — `components/service-card.blade.php`

```blade
{{--
    Usage:
    <x-service-card
        title="خدمات الطلاب"
        description="قدّم أو اطلع على نتائج طلباتك للمنحة والسكن والتأمين والنقل"
        icon="thumbs-up"
        route="student.services"
        button-label="الدخول"
    />
--}}

@props([
    'title'       => '',
    'description' => '',
    'icon'        => 'thumbs-up',   // thumbs-up | send | refresh | clipboard
    'route'       => '#',
    'buttonLabel' => 'الدخول',
    'accent'      => 'teal',        // teal | green
])

@php
$icons = [
    'thumbs-up' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017a2 2 0 01-1.415-.586l-4.244-4.243A2 2 0 015 14.757V10a2 2 0 012-2h1l2-4a1 1 0 011 1v1z"/>',
    'send'      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>',
    'refresh'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
    'clipboard' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
];
$bgIcon = $accent === 'green' ? 'bg-green-700' : 'bg-teal-500';
$borderAccent = $accent === 'green' ? 'border-t-green-700' : 'border-t-teal-500';
@endphp

<div class="bg-white rounded-2xl border border-gray-100 border-t-4 {{ $borderAccent }} shadow-sm hover:shadow-md transition-shadow p-6 flex flex-col items-center text-center gap-4">

    {{-- Icon --}}
    <div class="{{ $bgIcon }} rounded-2xl p-4 w-16 h-16 flex items-center justify-center shadow">
        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icons[$icon] ?? $icons['thumbs-up'] !!}
        </svg>
    </div>

    {{-- Text --}}
    <div>
        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $title }}</h3>
        <p class="text-gray-500 text-sm leading-relaxed">{{ $description }}</p>
    </div>

    {{-- Button --}}
    <a href="{{ route($route) }}"
       class="mt-auto w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border-2 border-green-600 text-green-700 font-semibold hover:bg-green-600 hover:text-white transition-all duration-200 text-sm">
        {{ $buttonLabel }}
        <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </a>

</div>
```

---

## 📣 Action Banner — `components/action-banner.blade.php`

```blade
{{--
    The highlighted full-width action rows (e.g. "نتائج خدمات الطلاب متاحة الآن")
--}}

@props([
    'title'       => '',
    'description' => '',
    'route'       => '#',
    'buttonLabel' => 'عرض نتائجي',
    'icon'        => 'user',
])

<div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 flex items-center justify-between gap-4 hover:shadow-md transition-shadow">

    {{-- Icon + Text --}}
    <div class="flex items-center gap-4">
        <div class="bg-green-100 rounded-xl p-3 text-green-700">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div class="text-right">
            <p class="font-bold text-gray-800 text-base">{{ $title }}</p>
            <p class="text-gray-500 text-sm">{{ $description }}</p>
        </div>
    </div>

    {{-- Button --}}
    <a href="{{ route($route) }}"
       class="flex-shrink-0 inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition text-sm shadow">
        <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        {{ $buttonLabel }}
    </a>

</div>
```



## 📦 Required Packages

```bash
# Install Laravel with Vite + Tailwind
composer create-project laravel/laravel platform
cd platform

npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p

# RTL support (optional but recommended)
npm install tailwindcss-rtl

# Icons (alternative to inline SVG)
npm install @heroicons/react  # if using React/Inertia
# OR use blade-heroicons for Blade
composer require blade-ui-kit/blade-heroicons
```

---

*Style guide version 1.0 — منصة طلبة التعليم العالي — 2025–2026*
