@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Utilisateurs</h1>
                <p class="mt-1 text-sm text-gray-500">Gerez les comptes, roles et rattachements aux institutions.</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-600">
                Nouvel utilisateur
            </a>
        </div>

        <form method="GET" class="mb-4 grid gap-3 rounded-lg border border-gray-200 bg-white p-3 shadow-sm md:grid-cols-[1fr_220px_auto]">
            <label>
                <span class="mb-1 block text-sm font-medium text-gray-700">Rechercher</span>
                <input type="text" name="q" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm" value="{{ request('q') }}" placeholder="Nom ou email">
            </label>
            <label>
                <span class="mb-1 block text-sm font-medium text-gray-700">Role</span>
                <select name="role" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <option value="">Tous</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                    @endforeach
                </select>
            </label>
            <div class="flex items-end">
                <button type="submit" class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700">Filtrer</button>
            </div>
        </form>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Roles</th>
                        <th class="px-4 py-3">Institution</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="rounded-full bg-primary-50 px-2 py-1 text-xs font-semibold text-primary-700">{{ $role }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $user->institution?->nom ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-primary hover:text-primary-600">Modifier</a>
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700">Supprimer</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-gray-500">Aucun utilisateur trouve.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
    </section>
</div>
@endsection
