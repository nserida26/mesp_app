@extends('layouts.app')

@section('title', __('lang.auth.forgot_password'))

@section('content')
    <form method="POST" action="{{ route('password.email') }}" class="mx-auto max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <h1 class="mb-5 text-2xl font-bold">@lang('lang.auth.forgot_password')</h1>
        <input name="email" type="email" placeholder="@lang('lang.auth.email')" required class="mb-4 w-full rounded-md border border-gray-300 px-3 py-2">
        <button class="w-full rounded-md bg-primary px-4 py-2 font-semibold text-white" type="submit">@lang('lang.auth.send_link')</button>
    </form>
@endsection
