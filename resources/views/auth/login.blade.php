@extends('layouts.app')

@section('title', __('lang.auth.login'))

@section('content')
    <form method="POST" action="{{ route('login') }}" class="mx-auto max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <h1 class="mb-5 text-2xl font-bold">@lang('lang.auth.admin_login')</h1>
        <label class="mb-4 block">
            <span class="mb-1 block text-sm font-medium">@lang('lang.auth.email')</span>
            <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-md border border-gray-300 px-3 py-2">
        </label>
        <label class="mb-4 block">
            <span class="mb-1 block text-sm font-medium">@lang('lang.auth.password')</span>
            <input name="password" type="password" required class="w-full rounded-md border border-gray-300 px-3 py-2">
        </label>
        @error('email') <p class="mb-3 text-sm text-red-600">{{ $message }}</p> @enderror
        <button class="w-full rounded-md bg-primary px-4 py-2 font-semibold text-white" type="submit">@lang('lang.auth.enter')</button>
    </form>
@endsection
