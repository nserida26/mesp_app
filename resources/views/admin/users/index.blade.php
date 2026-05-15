@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="layout-dashboard">
    @include('partials.sidebar')

    <div style="padding:2rem; overflow-x:auto;">

        <div class="d-flex justify-between align-center mb-3" style="flex-wrap:wrap; gap:1rem;">
            <div>
                <h1 style="font-size:1.5rem; margin-bottom:.2rem;">Utilisateurs</h1>
                <p class="text-muted" style="font-size:.87rem;">Gérez les comptes et leurs rôles</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus"></i> Nouvel utilisateur
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" class="card mb-3" style="padding:1rem 1.25rem;">
            <div style="display:grid; grid-template-columns: 1fr 1fr auto; gap:.75rem; align-items:end;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Rechercher</label>
                    <div class="input-wrap">
                        <span class="input-icon"><i class="bi bi-search"></i></span>
                        <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Nom, email…">
                    </div>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Rôle</label>
                    <div class="input-wrap">
                        <span class="input-icon"><i class="bi bi-person-gear"></i></span>
                        <select name="role" class="form-control">
                            <option value="">— Tous —</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                    {{ __('roles.'.$role->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline btn-sm">
                    <i class="bi bi-funnel"></i> Filtrer
                </button>
            </div>
        </form>

        {{-- Table --}}
        <div class="card">
            <div class="table-wrap" style="border:none;border-radius:0;">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle(s)</th>
                            <th>Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td style="color:var(--text-muted); font-size:.8rem;">{{ $user->id }}</td>
                            <td style="font-weight:500;">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->getRoleNames() as $role)
                                    <span class="badge badge-success" style="margin-inline-end:.25rem;">{{ $role }}</span>
                                @endforeach
                            </td>
                            <td style="font-size:.82rem; color:var(--text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline btn-icon btn-sm" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding:2rem;">
                                <i class="bi bi-inbox" style="font-size:1.5rem; display:block; margin-bottom:.5rem;"></i>
                                Aucun utilisateur trouvé
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
            <div style="padding:.85rem 1.25rem; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem;">
                <p class="text-muted" style="font-size:.83rem;">
                    {{ $users->firstItem() }}–{{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
                </p>
                <ul class="pagination">
                    {{ $users->withQueryString()->links('vendor.pagination.simple') }}
                </ul>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
