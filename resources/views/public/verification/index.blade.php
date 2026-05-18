@extends('layouts.public')

@section('title', __('lang.verification.title'))

@section('content')
    @php
        $verificationProps = [
            'formAction' => route('public.verify.check'),
            'csrfToken' => csrf_token(),
            'captcha' => $captcha,
            'labels' => [
                'securityCode' => __('lang.verification.security_code'),
                'copyCode' => __('lang.verification.copy_code'),
                'verify' => __('lang.nav.verify'),
            ],
            'tabs' => [
                [
                    'key' => 'student',
                    'label' => __('lang.nav.students'),
                    'inputName' => 'numero_national',
                    'inputLabel' => __('lang.verification.nni_number'),
                    'placeholder' => 'Exemple: 1000000001',
                ],
                [
                    'key' => 'teacher',
                    'label' => __('lang.teachers_public.title'),
                    'inputName' => 'numero_national',
                    'inputLabel' => __('lang.verification.teacher_nni_number'),
                    'placeholder' => 'Exemple: 2000000001',
                ],
                [
                    'key' => 'institution',
                    'label' => __('lang.nav.institutions'),
                    'inputName' => 'code_etablissement',
                    'inputLabel' => __('lang.public.institution_placeholder'),
                    'placeholder' => 'Exemple: INST-001',
                ],
                [
                    'key' => 'formation',
                    'label' => __('lang.nav.formations'),
                    'inputName' => 'code_filiere',
                    'inputLabel' => __('lang.nav.formations'),
                    'placeholder' => 'Exemple: FIL-2026-001',
                ],
            ],
        ];
    @endphp

    <section class="relative overflow-hidden bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <div
                    class="mb-5 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-4 py-1.5 text-sm font-semibold text-green-700 shadow-sm">
                    <i class="fas fa-shield-halved text-teal-500"></i>
                    @lang('lang.verification.secure_check')
                </div>
                <h1 class="text-4xl font-black leading-tight text-gray-900 md:text-5xl">
                    @lang('lang.verification.title')
                </h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-relaxed text-gray-600">
                    @lang('lang.verification.subtitle')
                </p>
            </div>
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 py-10">
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div data-vue-component="PublicVerification" data-props='@json($verificationProps)' v-cloak></div>
    </div>
@endsection
