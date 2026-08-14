@extends('layouts.public')

@section('title', __('lang.orientation.track_title'))

@section('content')
<section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-16">
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-center text-3xl font-black text-gray-900">@lang('lang.orientation.track_title')</h1>

        @if(session('error'))
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('public.orientation.suivi.consulter') }}" class="mt-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            @csrf
            <label class="mb-4 block">
                <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.track_code') *</span>
                <input name="code_suivi" value="{{ old('code_suivi') }}" required placeholder="ORI-2026-XXXXXX"
                       class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
            </label>
            <label class="mb-6 block">
                <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.track_nni') *</span>
                <input name="nni" value="{{ old('nni') }}" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
            </label>
            <button type="submit" class="w-full rounded-xl bg-green-700 px-6 py-3 font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-800">
                @lang('lang.orientation.track_submit')
            </button>
        </form>
    </div>
</section>
@endsection
