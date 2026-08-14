@extends('layouts.public')

@section('title', __('lang.orientation.confirmation_title'))

@section('content')
<section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-16">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 text-green-700">
            <i class="fas fa-check text-3xl"></i>
        </div>
        <h1 class="text-3xl font-black text-gray-900">@lang('lang.orientation.confirmation_title')</h1>

        <div class="mt-8 rounded-2xl border border-gray-100 bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-wide text-gray-400">@lang('lang.orientation.tracking_code_title')</p>
            <p class="mt-3 select-all font-mono text-3xl font-black tracking-wider text-green-700">{{ $code }}</p>
            <p class="mt-4 text-sm text-gray-500">@lang('lang.orientation.tracking_code_help')</p>
        </div>

        <a href="{{ route('public.orientation.suivi') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
            <i class="fas fa-magnifying-glass"></i>
            @lang('lang.orientation.track_title')
        </a>
    </div>
</section>
@endsection
