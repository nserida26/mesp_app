@extends('layouts.app')

@section('title', 'Validations')

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Validations</h1>
                <p class="mt-1 text-sm text-gray-500">Donnees soumises par les responsables d'etablissement.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.validations.index', ['tab' => 'en_attente']) }}"
                   class="rounded-xl px-4 py-2 text-sm font-semibold {{ $tab === 'en_attente' ? 'bg-primary text-white' : 'border border-gray-300 text-gray-700' }}">
                    En attente
                </a>
                <a href="{{ route('admin.validations.index', ['tab' => 'traitees']) }}"
                   class="rounded-xl px-4 py-2 text-sm font-semibold {{ $tab === 'traitees' ? 'bg-primary text-white' : 'border border-gray-300 text-gray-700' }}">
                    Traitees
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Element</th>
                        <th class="px-6 py-3">Type</th>
                        <th class="px-6 py-3">Etablissement</th>
                        <th class="px-6 py-3">Soumis par</th>
                        <th class="px-6 py-3">Statut</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($items as $entry)
                        @php
                            $model = $entry['model'];
                            $badge = $model->statut_validation_badge;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="font-semibold text-gray-900">{{ $model->nom_complet ?? trim(($model->nom ?? '') . ' ' . ($model->prenom ?? '')) }}</div>
                                @if ($model->statut_validation === 'rejete' && $model->motif_rejet)
                                    <div class="text-xs text-red-500">Motif : {{ $model->motif_rejet }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ ucfirst($entry['type']) }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $model->institution?->nom ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $model->creePar?->name ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            </td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    @if ($model->statut_validation === 'en_attente')
                                        <form method="POST" action="{{ route('admin.validations.approve', [$entry['type'], $model->uuid]) }}">
                                            @csrf
                                            <button class="rounded-lg border border-green-200 px-3 py-1.5 text-xs font-semibold text-green-700 hover:bg-green-50" type="submit">
                                                Approuver
                                            </button>
                                        </form>
                                        <button type="button" onclick="document.getElementById('reject-{{ $entry['type'] }}-{{ $model->uuid }}').classList.toggle('hidden')"
                                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-50">
                                            Rejeter
                                        </button>
                                    @endif
                                </div>
                                <form id="reject-{{ $entry['type'] }}-{{ $model->uuid }}" method="POST"
                                      action="{{ route('admin.validations.reject', [$entry['type'], $model->uuid]) }}"
                                      class="hidden mt-2 flex gap-2">
                                    @csrf
                                    <input type="text" name="motif" required placeholder="Motif du rejet"
                                           class="w-64 rounded-md border border-gray-300 px-2 py-1 text-xs">
                                    <button class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700" type="submit">
                                        Confirmer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox mb-3 block text-4xl text-gray-200"></i>
                                Aucun element.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
