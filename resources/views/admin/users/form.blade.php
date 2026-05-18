@extends('layouts.app')

@section('title', isset($user) ? 'Modifier utilisateur' : 'Nouvel utilisateur')

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 p-4 md:p-8">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ isset($user) ? 'Modifier utilisateur' : 'Nouvel utilisateur' }}</h1>
                <p class="mt-1 text-sm text-gray-500">Compte, role et institution associee.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Retour</a>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                @foreach($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" class="max-w-3xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">Nom complet <span class="text-red-600">*</span></span>
                    <input type="text" name="name" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm" value="{{ old('name', $user->name ?? '') }}" required>
                    @error('name')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">Email <span class="text-red-600">*</span></span>
                    <input type="email" name="email" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm" value="{{ old('email', $user->email ?? '') }}" required>
                    @error('email')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </label>

                <label class="block md:col-span-2">
                    <span class="mb-1 block text-sm font-medium text-gray-700">Institution</span>
                    <select name="institution_id" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                        <option value="">Aucune institution</option>
                        @foreach($institutions as $institution)
                            <option value="{{ $institution->id }}" @selected((string) old('institution_id', $user->institution_id ?? '') === (string) $institution->id)>
                                {{ $institution->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('institution_id')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </label>

                <fieldset class="md:col-span-2">
                    <legend class="mb-2 block text-sm font-medium text-gray-700">Roles <span class="text-red-600">*</span></legend>
                    <div class="grid gap-2 rounded-md border border-gray-200 p-3 md:grid-cols-3">
                        @foreach($roles as $role)
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-gray-300 text-primary" @checked((isset($user) && $user->hasRole($role->name)) || in_array($role->name, old('roles', []), true))>
                                {{ $role->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('roles')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </fieldset>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">Mot de passe {{ isset($user) ? '(optionnel)' : '*' }}</span>
                    <input type="password" name="password" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm" {{ !isset($user) ? 'required' : '' }}>
                    @error('password')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">Confirmation</span>
                    <input type="password" name="password_confirmation" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                </label>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="rounded-md bg-primary px-5 py-2 text-sm font-semibold text-white hover:bg-primary-600">Enregistrer</button>
                <a href="{{ route('admin.users.index') }}" class="rounded-md border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
