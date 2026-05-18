@extends('layouts.app')

@section('title', __('lang.auth.register'))

@section('content')
    <form method="POST" action="{{ route('register') }}" class="mx-auto max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <h1 class="mb-5 text-2xl font-bold">@lang('lang.auth.create_account')</h1>
        <input name="name" placeholder="@lang('lang.auth.name')" required class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2">
        <input name="email" type="email" placeholder="@lang('lang.auth.email')" required class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2">
        <input name="password" type="password" placeholder="@lang('lang.auth.password')" required class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2">
        <input name="password_confirmation" type="password" placeholder="@lang('lang.auth.confirmation')" required class="mb-4 w-full rounded-md border border-gray-300 px-3 py-2">
        <button class="w-full rounded-md bg-primary px-4 py-2 font-semibold text-white" type="submit">@lang('lang.auth.register')</button>
    </form>
@endsection
