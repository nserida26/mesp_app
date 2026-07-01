@extends('layouts.auth')

@section('title', __('lang.auth.login'))

@section('content')

    {{-- Header --}}
    <div class="mb-8">
        <div class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-green-700 shadow-lg shadow-green-200">
            <i class="fas fa-shield-halved text-2xl text-white"></i>
        </div>
        <h1 class="text-2xl font-black text-gray-900">@lang('lang.auth.login')</h1>
        <p class="mt-1 text-sm text-gray-500">Espace administration — @lang('lang.brand')</p>
    </div>

    {{-- Error alert --}}
    @if ($errors->any())
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="fas fa-circle-exclamation mt-0.5 shrink-0 text-red-400"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">
                @lang('lang.auth.email')
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3.5 text-gray-400">
                    <i class="fas fa-envelope text-sm"></i>
                </span>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 ps-10 pe-4 text-sm text-gray-900 transition placeholder:text-gray-400 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500/20 @error('email') border-red-400 focus:border-red-400 focus:ring-red-400/20 @enderror"
                    placeholder="admin@exemple.mr">
            </div>
        </div>

        {{-- Password --}}
        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="password" class="text-sm font-semibold text-gray-700">
                    @lang('lang.auth.password')
                </label>
                <a href="{{ route('password.request') }}"
                   class="text-xs font-medium text-green-700 transition hover:text-green-900 hover:underline">
                    @lang('lang.auth.forgot_password')
                </a>
            </div>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3.5 text-gray-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input id="password" name="password" type="password" required
                    autocomplete="current-password"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 ps-10 pe-11 text-sm text-gray-900 transition placeholder:text-gray-400 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500/20"
                    placeholder="••••••••">
                <button type="button" tabindex="-1" onclick="togglePassword()"
                    class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye text-sm" id="pw-eye"></i>
                </button>
            </div>
        </div>

        {{-- Remember me --}}
        <label class="flex cursor-pointer items-center gap-2.5 text-sm text-gray-600">
            <input type="checkbox" name="remember" id="remember"
                class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
            Se souvenir de moi
        </label>

        {{-- Submit --}}
        <button type="submit"
            class="mt-1 w-full rounded-xl bg-green-700 px-4 py-3 text-sm font-bold text-white shadow-md shadow-green-100 transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 active:scale-[.98]">
            <i class="fas fa-arrow-right-to-bracket me-2"></i>
            @lang('lang.auth.enter')
        </button>
    </form>

    {{-- Back to public --}}
    <div class="mt-8 text-center">
        <a href="{{ route('public.home') }}"
           class="inline-flex items-center gap-1.5 text-xs text-gray-400 transition hover:text-green-700">
            <i class="fas fa-globe text-xs"></i>
            @lang('lang.nav.public_space')
        </a>
    </div>

@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const eye   = document.getElementById('pw-eye');
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush
