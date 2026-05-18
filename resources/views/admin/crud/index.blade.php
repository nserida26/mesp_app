@extends('layouts.app')

@section('title', $config['label'])

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $config['label'] }}</h1>
                <p class="mt-1 text-sm text-gray-500">Creation, modification, consultation et suppression.</p>
            </div>
            <a href="{{ route('admin.resources.create', $resource) }}" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-600">
                Nouveau
            </a>
        </div>

        <form method="GET" class="mb-4 flex gap-2 rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
            <input name="q" value="{{ request('q') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm" placeholder="Rechercher...">
            <button class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700" type="submit">Filtrer</button>
        </form>

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Element</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3">Creation</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($items as $item)
                        @php
                            $title = collect($config['title'])->map(fn ($field) => $item->{$field} ?? null)->filter()->implode(' - ');
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $title ?: ($item->uuid ?? $item->id) }}</div>
                                <div class="text-xs text-gray-500">#{{ $item->id ?? $item->uuid }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-primary-50 px-2 py-1 text-xs font-semibold text-primary-700">
                                    {{ $item->statut ?? $item->role ?? 'actif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-3">
                                    <a class="text-primary hover:text-primary-600" href="{{ route('admin.resources.show', [$resource, $item->uuid ?? $item->id]) }}">Voir</a>
                                    <a class="text-gray-700 hover:text-gray-900" href="{{ route('admin.resources.edit', [$resource, $item->uuid ?? $item->id]) }}">Modifier</a>
                                    <form method="POST" action="{{ route('admin.resources.destroy', [$resource, $item->uuid ?? $item->id]) }}" onsubmit="return confirm('Supprimer cet element ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-700" type="submit">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-gray-500">Aucune donnee.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $items->links() }}</div>
    </section>
</div>
@endsection
