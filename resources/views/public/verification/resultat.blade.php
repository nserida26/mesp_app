@extends('layouts.public')

@section('title', __('lang.verification.result_title'))

@section('content')
    @php
        $isValid = isset($resultat) && ($resultat['status'] ?? null) === 'valide';
        $academicRows = [
            __('lang.verification.study_level') => !empty($resultat['niveau']) ? ucfirst($resultat['niveau']) : null,
            __('lang.verification.program') => $resultat['filiere'] ?? null,
            __('lang.verification.academic_year') => $resultat['annee'] ?? null,
            __('lang.verification.current_semester') => !empty($resultat['semestre']) ? __('lang.students_public.semester') . ' ' . $resultat['semestre'] : null,
        ];
        $institutionRows = [
            __('lang.verification.institution') => $resultat['institution'] ?? null,
            __('lang.public.city') => $resultat['ville'] ?? null,
            __('lang.students_public.status') => $resultat['statut'] ?? null,
            __('lang.public.valid_until') => $resultat['date_fin'] ?? null,
            __('lang.verification.registration_number') => $resultat['numero_inscription'] ?? null,
        ];
        $teacherRows = [
            __('lang.verification.full_name') => !empty($resultat['prenom']) || !empty($resultat['nom']) ? trim(($resultat['prenom'] ?? '') . ' ' . ($resultat['nom'] ?? '')) : null,
            __('lang.verification.nni_number') => $resultat['numero_national'] ?? null,
            __('lang.verification.accreditation_number') => $resultat['numero_accreditation'] ?? null,
            __('lang.verification.grade') => $resultat['grade'] ?? null,
            __('lang.verification.speciality') => $resultat['specialite'] ?? null,
        ];
    @endphp

    <section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div
                    class="mb-4 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-4 py-1.5 text-sm font-semibold text-green-700 shadow-sm">
                    <i class="fas fa-clipboard-check text-teal-500"></i>
                    @lang('lang.verification.result_title')
                </div>
                <h1 class="text-3xl font-black text-gray-900">
                    {{ $isValid ? __('lang.verification.valid_registration') : __('lang.verification.invalid_title') }}
                </h1>
                <p class="mx-auto mt-3 max-w-2xl text-gray-600">
                    {{ $isValid ? __('lang.verification.valid_message') : __('lang.verification.invalid_message') }}
                </p>
            </div>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl shadow-green-100/50">
            @if ($isValid)
                <div class="bg-green-700 px-6 py-8 text-center text-white">
                    <i class="fas fa-check-circle text-5xl mb-3"></i>
                    <h2 class="text-2xl font-bold">@lang('lang.verification.valid_registration')</h2>
                    <p class="mt-2 text-green-100">@lang('lang.verification.valid_message')</p>
                </div>

                <div class="p-5 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @if (($resultat['type'] ?? null) === 'teacher' && collect($teacherRows)->filter()->isNotEmpty())
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                                <h3 class="font-semibold text-gray-900 text-lg mb-4 flex items-center">
                                    <i class="fas fa-chalkboard-teacher text-green-600 mr-2"></i>
                                    @lang('lang.verification.teacher_info')
                                </h3>
                                <dl class="space-y-3">
                                    @foreach ($teacherRows as $label => $value)
                                        @if (!empty($value))
                                            <div class="rounded-xl bg-white p-3 shadow-sm">
                                                <dt class="text-xs font-semibold uppercase text-gray-500">{{ $label }}</dt>
                                                <dd class="text-lg font-medium text-gray-900">{{ $value }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        @endif

                        @if (collect($academicRows)->filter()->isNotEmpty())
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                                <h3 class="font-semibold text-gray-900 text-lg mb-4 flex items-center">
                                    <i class="fas fa-graduation-cap text-green-600 mr-2"></i>
                                    @lang('lang.verification.academic_info')
                                </h3>
                                <dl class="space-y-3">
                                    @foreach ($academicRows as $label => $value)
                                        @if (!empty($value))
                                            <div class="rounded-xl bg-white p-3 shadow-sm">
                                                <dt class="text-xs font-semibold uppercase text-gray-500">{{ $label }}</dt>
                                                <dd class="text-lg font-medium text-gray-900">{{ $value }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            </div>
                        @endif

                        @if (collect($institutionRows)->filter()->isNotEmpty())
                            <div class="rounded-2xl border border-gray-100 bg-gray-50 p-5">
                                <h3 class="font-semibold text-gray-900 text-lg mb-4 flex items-center">
                                    <i class="fas fa-university text-green-600 mr-2"></i>
                                    @lang('lang.verification.institution_info')
                                </h3>
                                <dl class="space-y-3">
                                    @foreach ($institutionRows as $label => $value)
                                        @if (!empty($value))
                                            <div class="rounded-xl bg-white p-3 shadow-sm">
                                                <dt class="text-xs font-semibold uppercase text-gray-500">{{ $label }}</dt>
                                                <dd class="text-lg font-medium text-gray-900">{{ $value }}</dd>
                                            </div>
                                        @endif
                                    @endforeach

                                    @if (!empty($resultat['institution']) && empty($resultat['statut']))
                                        <div class="rounded-xl bg-white p-3 shadow-sm">
                                            <dt class="text-xs font-semibold uppercase text-gray-500">@lang('lang.verification.accreditation_status')</dt>
                                            <dd class="text-green-600 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                @lang('lang.verification.accredited')
                                            </dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 p-4">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-certificate text-green-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-green-800">
                                    <strong>@lang('lang.verification.certified_title')</strong><br>
                                    {{ __('lang.verification.certified_text', ['date' => now()->format('d/m/Y H:i')]) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-red-600 text-white px-6 py-8 text-center">
                    <i class="fas fa-exclamation-triangle text-5xl mb-3"></i>
                    <h2 class="text-2xl font-bold">@lang('lang.verification.invalid_title')</h2>
                    <p class="mt-2 text-red-100">@lang('lang.verification.invalid_message')</p>
                </div>

                <div class="p-5 md:p-8">
                    <div class="text-center">
                        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-6">
                            <i class="fas fa-info-circle text-red-500 text-2xl mb-3"></i>
                            <p class="text-gray-700 mb-4">@lang('lang.verification.invalid_help')</p>

                            <div class="rounded-2xl border border-gray-100 bg-white p-4 text-left shadow-sm">
                                <h4 class="font-semibold text-gray-900 mb-2">@lang('lang.verification.possible_causes')</h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-start">
                                        <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                        <span>@lang('lang.verification.cause_wrong_nni')</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                        <span>@lang('lang.verification.cause_not_registered')</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                        <span>@lang('lang.verification.cause_not_declared')</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                        <span>@lang('lang.verification.cause_not_accredited')</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('public.verify') }}"
                                class="inline-flex items-center justify-center rounded-xl bg-green-700 px-6 py-3 font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-800">
                                <i class="fas fa-redo mr-2"></i>
                                @lang('lang.verification.new_verification')
                            </a>

                            <a href="{{ route('public.institutions') }}"
                                class="inline-flex items-center justify-center rounded-xl bg-gray-700 px-6 py-3 font-semibold text-white transition hover:bg-gray-800">
                                <i class="fas fa-building mr-2"></i>
                                @lang('lang.verification.view_institutions')
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <div class="flex gap-3">
                        <a href="{{ route('public.verify') }}" class="font-semibold text-green-700 hover:text-green-900">
                            <i class="fas fa-arrow-left mr-1"></i>
                            @lang('lang.verification.new_search')
                        </a>

                        <span class="text-gray-300">|</span>

                        <a href="{{ route('public.home') }}" class="font-semibold text-gray-600 hover:text-gray-900">
                            <i class="fas fa-home mr-1"></i>
                            @lang('lang.nav.home')
                        </a>
                    </div>

                    <button onclick="window.print()" class="font-semibold text-gray-600 hover:text-gray-900">
                        <i class="fas fa-print mr-1"></i>
                        @lang('lang.actions.print')
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-6 text-center text-xs text-gray-500">
            <p>
                <i class="fas fa-gavel mr-1"></i>
                @lang('lang.verification.official_notice')
            </p>
            <p class="mt-1">
                @lang('lang.verification.verification_date') : {{ now()->format('d/m/Y H:i:s') }} |
                @lang('lang.verification.reference') : VERIF-{{ now()->format('Ymd') }}-{{ random_int(1000, 9999) }}
            </p>
        </div>
    </div>
@endsection
