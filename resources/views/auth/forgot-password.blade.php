@extends('layouts.auth')

@section('title', __('lang.auth.forgot_password'))

@section('content')
    <div class="mb-8">
        <a href="{{ route('login') }}"
           class="mb-6 inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-green-700">
            <i class="fas fa-arrow-left text-xs"></i>
            @lang('lang.auth.login')
        </a>
        <span class="mt-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
            <i class="fas fa-key text-xl"></i>
        </span>
        <h1 class="mt-4 text-2xl font-black text-gray-900">@lang('lang.auth.forgot_password')</h1>
        <p class="mt-1 text-sm text-gray-500">Entrez votre email pour recevoir un lien de réinitialisation.</p>
    </div>

    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="fas fa-circle-check mt-0.5 shrink-0 text-green-500"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="fas fa-circle-exclamation mt-0.5 shrink-0 text-red-400"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700">
                @lang('lang.auth.email')
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3.5 text-gray-400">
                    <i class="fas fa-envelope text-sm"></i>
                </span>
                <input id="email" name="email" type="email" required autofocus
                    value="{{ old('email') }}"
                    class="w-full rounded-xl border border-gray-300 bg-white py-2.5 ps-10 pe-4 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20"
                    placeholder="admin@exemple.mr">
            </div>
        </div>

        <button type="submit"
            class="w-full rounded-xl bg-green-700 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-green-100 transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
            <i class="fas fa-paper-plane me-2"></i>
            @lang('lang.auth.send_link')
        </button>
    </form>
@endsection
