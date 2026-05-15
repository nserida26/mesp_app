@extends('layouts.app')

@section('title', isset($user) ? 'Modifier l\'utilisateur' : 'Nouvel utilisateur')

@section('content')
<div class="layout-dashboard">
    @include('partials.sidebar')

    <div style="padding:2rem; overflow-x:auto;">

        <div class="d-flex align-center gap-2 mb-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-sm">
                <i class="bi bi-arrow-start"></i> Retour
            </a>
            <h1 style="font-size:1.4rem; margin:0;">
                {{ isset($user) ? 'Modifier : ' . $user->name : 'Nouvel utilisateur' }}
            </h1>
        </div>

        <div class="card" style="max-width:640px;">
            <div class="card-header">
                <i class="bi bi-person-badge"></i>
                Informations du compte
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle-fill"></i>
                        <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
                    </div>
                @endif

                <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" novalidate>
                    @csrf
                    @if(isset($user)) @method('PUT') @endif

                    <div class="form-group">
                        <label class="form-label">Nom complet <span class="required">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name ?? '') }}" required>
                        </div>
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email ?? '') }}" required>
                        </div>
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Roles --}}
                    <div class="form-group">
                        <label class="form-label">Rôle(s) <span class="required">*</span></label>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:.5rem; padding:.75rem; border:1.5px solid var(--border-dark); border-radius:var(--radius-md); background:var(--bg);">
                            @foreach($roles as $role)
                            <label style="display:flex; align-items:center; gap:.5rem; cursor:pointer; font-size:.87rem; color:var(--text-secondary);">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    style="width:15px;height:15px;accent-color:var(--green);cursor:pointer;"
                                    {{ (isset($user) && $user->hasRole($role->name)) || in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
                                {{ __('roles.'.$role->name) }}
                            </label>
                            @endforeach
                        </div>
                        @error('roles')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <hr class="divider">

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                Mot de passe {{ isset($user) ? '(laisser vide = inchangé)' : '' }}
                                @if(!isset($user))<span class="required">*</span>@endif
                            </label>
                            <div class="input-wrap">
                                <span class="input-icon"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    {{ !isset($user) ? 'required' : '' }}>
                            </div>
                            @error('password')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <div class="input-wrap">
                                <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2" style="margin-top:1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2-circle"></i>
                            {{ isset($user) ? 'Enregistrer' : 'Créer l\'utilisateur' }}
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
