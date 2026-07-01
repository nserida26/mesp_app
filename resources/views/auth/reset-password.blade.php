@extends('layouts.auth')

@section('title', __('lang.auth.reset_password'))

@section('content')
    <div class="mb-8">
        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
            <i class="fas fa-lock-open text-xl"></i>
        </span>
        <h1 class="mt-4 text-2xl font-black text-gray-900">@lang('lang.auth.reset_password')</h1>
        <p class="mt-1 text-sm text-gray-500">Choisissez un nouveau mot de passe sécurisé.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="fas fa-circle-exclamation mt-0.5 shrink-0 text-red-400"></i>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">
                @lang('lang.auth.new_password')
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3.5 text-gray-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input id="password" name="password" type="password" required autofocus
                    class="w-full rounded-xl border border-gray-300 bg-white py-2.5 ps-10 pe-11 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20"
                    placeholder="••••••••">
                <button type="button" tabindex="-1" onclick="togglePw('password','eye1')"
                    class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye text-sm" id="eye1"></i>
                </button>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700">
                @lang('lang.auth.confirmation')
            </label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3.5 text-gray-400">
                    <i class="fas fa-lock text-sm"></i>
                </span>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded-xl border border-gray-300 bg-white py-2.5 ps-10 pe-11 text-sm shadow-sm transition placeholder:text-gray-400 focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20"
                    placeholder="••••••••">
                <button type="button" tabindex="-1" onclick="togglePw('password_confirmation','eye2')"
                    class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-eye text-sm" id="eye2"></i>
                </button>
            </div>
        </div>

        <button type="submit"
            class="w-full rounded-xl bg-green-700 px-4 py-3 text-sm font-semibold text-white shadow-md shadow-green-100 transition hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
            <i class="fas fa-check me-2"></i>
            @lang('lang.auth.update_password')
        </button>
    </form>
@endsection

@push('scripts')
<script>
    function togglePw(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye   = document.getElementById(eyeId);
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
