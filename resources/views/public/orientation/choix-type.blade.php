@extends('layouts.public')

@section('title', __('lang.orientation.choose_path_title'))

@section('content')
<section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-3xl text-center">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-4 py-1.5 text-sm font-semibold text-green-700 shadow-sm">
                <i class="fas fa-route text-teal-500"></i>
                @lang('lang.orientation.nav_label')
            </div>
            <h1 class="text-4xl font-black text-gray-900">@lang('lang.orientation.choose_path_title')</h1>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-6 md:grid-cols-2">
            <a href="{{ route('public.orientation.formulaire', 'bac-licence') }}"
               class="group overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-green-100 text-green-700 group-hover:bg-green-700 group-hover:text-white">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">@lang('lang.orientation.path_bac_licence')</h2>
                <p class="mt-2 text-sm text-gray-500">@lang('lang.orientation.path_bac_licence_help')</p>
            </a>

            <a href="{{ route('public.orientation.formulaire', 'licence-master') }}"
               class="group overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-teal-100 text-teal-700 group-hover:bg-teal-600 group-hover:text-white">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900">@lang('lang.orientation.path_licence_master')</h2>
                <p class="mt-2 text-sm text-gray-500">@lang('lang.orientation.path_licence_master_help')</p>
            </a>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('public.orientation.suivi') }}" class="text-sm font-semibold text-green-700 hover:text-green-900">
                <i class="fas fa-magnifying-glass mr-1"></i>
                @lang('lang.orientation.track_title')
            </a>
        </div>
    </div>
</section>
@endsection
