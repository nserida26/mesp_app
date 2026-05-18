@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <form method="POST" action="{{ route('profile.update') }}" class="max-w-xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @method('PATCH')
        <h1 class="mb-5 text-2xl font-bold">Profil</h1>
        <input name="name" value="{{ old('name', $user->name) }}" required class="mb-3 w-full rounded-md border border-gray-300 px-3 py-2">
        <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="mb-4 w-full rounded-md border border-gray-300 px-3 py-2">
        <button class="rounded-md bg-primary px-4 py-2 font-semibold text-white" type="submit">@lang('lang.actions.save')</button>
    </form>
@endsection
