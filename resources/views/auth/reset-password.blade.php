@extends('layouts.app')

@section('title', __('lang.auth.reset_password'))

@section('content')
    <form method="POST" action="{{ route('password.store') }}" class="mx-auto max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">
        <h1 class="mb-5 text-2xl font-bold">@lang('lang.auth.new_password')</h1>
        <input name="password" type="password" placeholder="@lang('lang.auth.password')" required class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2">
        <input name="password_confirmation" type="password" placeholder="@lang('lang.auth.confirmation')" required class="mb-4 w-full rounded-md border border-gray-300 px-3 py-2">
        <button class="w-full rounded-md bg-primary px-4 py-2 font-semibold text-white" type="submit">@lang('lang.auth.update_password')</button>
    </form>
@endsection
