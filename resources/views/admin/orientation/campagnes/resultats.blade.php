@extends('layouts.app')

@section('title', $campagne->nom)

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Resultats &mdash; {{ $campagne->nom }}</h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.orientation.campagnes.resultats.export', $campagne) }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-file-csv"></i> Exporter CSV
                </a>
                <a href="{{ route('admin.orientation.campagnes.show', $campagne) }}"
                   class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-arrow-left me-1"></i> @lang('lang.actions.back')
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif

        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-green-700 p-6 text-center text-white shadow-md">
                <div class="mb-1 text-3xl font-black">{{ $stats['total'] }}</div>
                <div class="text-sm text-green-50">Total candidats traites</div>
            </div>
            <div class="rounded-2xl bg-blue-500 p-6 text-center text-white shadow-md">
                <div class="mb-1 text-3xl font-black">{{ $stats['orientees'] }}</div>
                <div class="text-sm text-blue-50">@lang('lang.orientation.status_orientee')</div>
            </div>
            <div class="rounded-2xl bg-gray-500 p-6 text-center text-white shadow-md">
                <div class="mb-1 text-3xl font-black">{{ $stats['non_orientees'] }}</div>
                <div class="text-sm text-gray-100">@lang('lang.orientation.status_non_orientee')</div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">@lang('lang.orientation.form_full_name')</th>
                        <th class="px-6 py-3">@lang('lang.fields.moyenne_generale')</th>
                        <th class="px-6 py-3">@lang('lang.crud.status')</th>
                        <th class="px-6 py-3">Formation</th>
                        <th class="px-6 py-3">Choix n&deg;</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($orientations as $o)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $o->candidat->nom_complet }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $o->moyenne }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $o->statut === 'orientee' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    @lang('lang.orientation.status_' . $o->statut)
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-600">
                                @if($o->offre)
                                    {{ $o->offre->filiere->nom }} &mdash; {{ $o->offre->filiere->institution->nom }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $o->ordre_choix ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox mb-3 block text-4xl text-gray-200"></i>
                                Aucun resultat. Lancez l'affectation depuis la fiche de la campagne (une fois fermee).
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $orientations->links() }}</div>
    </section>
</div>
@endsection
