@extends('layouts.public')

@section('title', __('lang.nav.home'))

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <div
                    class="mb-6 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-4 py-1.5 text-sm font-semibold text-green-700 shadow-sm">
                    <i class="fas fa-landmark text-teal-500"></i>
                    @lang('lang.public.hero_badge')
                </div>

                <h1 class="mx-auto max-w-3xl text-4xl font-black leading-tight text-gray-900 md:text-5xl">
                    @lang('lang.public.hero_title')
                </h1>
                <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-gray-600">
                    @lang('lang.public.hero_subtitle')
                </p>

                <div class="mx-auto mt-10 max-w-3xl">
                    <form action="{{ route('public.verify.check') }}" method="POST"
                        class="rounded-2xl border border-gray-100 bg-white p-5 text-left shadow-xl shadow-green-100/70 md:p-6">
                        @csrf
                        <input type="hidden" name="type" value="student">
                        <div class="mb-4 flex items-center gap-3 text-gray-900">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-700 text-white">
                                <i class="fas fa-shield-alt"></i>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-green-700">@lang('lang.public.quick_verify')</p>
                                <h2 class="text-lg font-bold">@lang('lang.public.verify_service_title')</h2>
                            </div>
                        </div>

                        <div class="grid gap-3 md:grid-cols-[1fr_auto]">
                            <input type="text" name="numero_national" placeholder="@lang('lang.public.nni_placeholder')"
                                class="min-h-12 w-full rounded-xl border-gray-200 text-gray-900 shadow-sm focus:border-green-500 focus:ring-green-500"
                                required>
                            <button type="submit"
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-green-700 px-6 py-3 font-bold text-white shadow-lg shadow-green-200 transition hover:bg-green-800">
                                <i class="fas fa-search mr-2"></i>@lang('lang.nav.verify')
                            </button>
                        </div>
                        <div class="mt-3 grid gap-2 sm:grid-cols-[auto_1fr] sm:items-center">
                            <div class="rounded-xl bg-gray-100 px-4 py-3 text-center font-mono text-sm font-bold text-gray-800">
                                {{ $captcha }}
                            </div>
                            <input type="text" name="captcha" maxlength="6" placeholder="@lang('lang.verification.copy_code')"
                                class="w-full rounded-xl border-gray-200 text-gray-900 shadow-sm focus:border-green-500 focus:ring-green-500"
                                required>
                        </div>
                        <p class="mt-3 text-xs text-gray-500">
                            <i class="fas fa-lock text-green-600 mr-1"></i>
                            @lang('lang.public.secure_data')
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900">@lang('lang.public.stats_title')</h2>
                <p class="mt-2 text-gray-500">@lang('lang.public.stats_subtitle')</p>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-4 md:p-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-green-700 px-6 py-5 text-center text-white shadow-md">
                        <i class="fas fa-building mb-3 text-3xl text-green-100"></i>
                        <div class="text-3xl font-black tracking-tight">{{ number_format($stats['institutions']) }}</div>
                        <div class="mt-1 text-sm font-medium text-green-50">@lang('lang.public.accredited_institutions')</div>
                    </div>

                    <div class="rounded-2xl bg-teal-500 px-6 py-5 text-center text-white shadow-md">
                        <i class="fas fa-graduation-cap mb-3 text-3xl text-teal-50"></i>
                        <div class="text-3xl font-black tracking-tight">{{ number_format($stats['filieres']) }}</div>
                        <div class="mt-1 text-sm font-medium text-teal-50">@lang('lang.public.authorized_formations')</div>
                    </div>

                    <div class="rounded-2xl bg-blue-500 px-6 py-5 text-center text-white shadow-md">
                        <i class="fas fa-users mb-3 text-3xl text-blue-50"></i>
                        <div class="text-3xl font-black tracking-tight">{{ number_format($stats['etudiants']) }}</div>
                        <div class="mt-1 text-sm font-medium text-blue-50">@lang('lang.public.registered_students')</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900">@lang('lang.public.services_title')</h2>
                <p class="mt-2 text-gray-500">@lang('lang.public.services_subtitle')</p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('public.verify') }}"
                    class="group flex flex-col items-center gap-4 rounded-2xl border border-gray-100 border-t-4 border-t-green-700 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-700 text-white shadow">
                        <i class="fas fa-clipboard-check text-2xl"></i>
                    </span>
                    <span>
                        <span class="block text-lg font-bold text-gray-900">@lang('lang.public.verify_service_title')</span>
                        <span class="mt-2 block text-sm leading-relaxed text-gray-500">@lang('lang.public.verify_service_description')</span>
                    </span>
                    <span class="mt-auto inline-flex items-center gap-2 rounded-xl border-2 border-green-600 px-4 py-2 text-sm font-semibold text-green-700 transition group-hover:bg-green-700 group-hover:text-white">
                        @lang('lang.nav.verify')
                        <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </a>

                <a href="{{ route('public.institutions') }}"
                    class="group flex flex-col items-center gap-4 rounded-2xl border border-gray-100 border-t-4 border-t-teal-500 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-500 text-white shadow">
                        <i class="fas fa-university text-2xl"></i>
                    </span>
                    <span>
                        <span class="block text-lg font-bold text-gray-900">@lang('lang.nav.institutions')</span>
                        <span class="mt-2 block text-sm leading-relaxed text-gray-500">@lang('lang.public.institutions_service_description')</span>
                    </span>
                    <span class="mt-auto inline-flex items-center gap-2 rounded-xl border-2 border-green-600 px-4 py-2 text-sm font-semibold text-green-700 transition group-hover:bg-green-700 group-hover:text-white">
                        @lang('lang.actions.view')
                        <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </a>

                <a href="{{ route('public.filieres') }}"
                    class="group flex flex-col items-center gap-4 rounded-2xl border border-gray-100 border-t-4 border-t-green-700 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-700 text-white shadow">
                        <i class="fas fa-book-open text-2xl"></i>
                    </span>
                    <span>
                        <span class="block text-lg font-bold text-gray-900">@lang('lang.nav.formations')</span>
                        <span class="mt-2 block text-sm leading-relaxed text-gray-500">@lang('lang.public.formations_service_description')</span>
                    </span>
                    <span class="mt-auto inline-flex items-center gap-2 rounded-xl border-2 border-green-600 px-4 py-2 text-sm font-semibold text-green-700 transition group-hover:bg-green-700 group-hover:text-white">
                        @lang('lang.actions.details')
                        <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </a>

                <a href="{{ route('public.etudiants') }}"
                    class="group flex flex-col items-center gap-4 rounded-2xl border border-gray-100 border-t-4 border-t-teal-500 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-500 text-white shadow">
                        <i class="fas fa-chart-simple text-2xl"></i>
                    </span>
                    <span>
                        <span class="block text-lg font-bold text-gray-900">@lang('lang.nav.students')</span>
                        <span class="mt-2 block text-sm leading-relaxed text-gray-500">@lang('lang.public.students_service_description')</span>
                    </span>
                    <span class="mt-auto inline-flex items-center gap-2 rounded-xl border-2 border-green-600 px-4 py-2 text-sm font-semibold text-green-700 transition group-hover:bg-green-700 group-hover:text-white">
                        @lang('lang.actions.view')
                        <i class="fas fa-arrow-right text-xs"></i>
                    </span>
                </a>
            </div>
        </div>
    </section>

    <section class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold text-gray-900">@lang('lang.public.recent_institutions')</h2>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($institutions_recentes as $institution)
                    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md">
                        <div class="p-6">
                            <div class="mb-4 flex items-center">
                                @if ($institution->logo_path)
                                    <img src="{{ Storage::url($institution->logo_path) }}" alt="{{ $institution->nom }}"
                                        class="mr-3 h-12 w-12 rounded-full object-cover">
                                @else
                                    <div class="mr-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                        <i class="fas fa-university text-xl text-green-600"></i>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $institution->nom }}</h3>
                                    <p class="text-sm text-gray-500">{{ $institution->ville }}</p>
                                </div>
                            </div>

                            @if ($institution->accreditationActive)
                                <div class="mb-2 rounded-xl bg-green-50 px-3 py-2 text-sm text-green-700">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    @lang('lang.public.valid_until')
                                    {{ $institution->accreditationActive->date_fin->format('d/m/Y') }}
                                </div>
                            @endif

                            <a href="{{ route('public.institutions.show', $institution->uuid) }}"
                                class="mt-4 inline-flex items-center gap-2 font-semibold text-green-700 hover:text-green-900">
                                @lang('lang.actions.details') <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
