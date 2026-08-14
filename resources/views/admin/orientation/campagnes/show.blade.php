@extends('layouts.app')

@section('title', $campagne->nom)

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $campagne->nom }}</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $campagne->type_orientation === 'bac_licence' ? __('lang.orientation.path_bac_licence') : __('lang.orientation.path_licence_master') }}
                    &middot; {{ $campagne->annee_universitaire }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50"
                   href="{{ route('admin.orientation.campagnes.edit', $campagne) }}">
                    <i class="fas fa-pen-to-square me-1"></i> @lang('lang.actions.edit')
                </a>
                @if($campagne->statut === 'fermee')
                    <form method="POST" action="{{ route('admin.orientation.campagnes.affectation', $campagne) }}"
                          onsubmit="return confirm('{{ __('lang.orientation.affectation_launched') }}?')">
                        @csrf
                        <button class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-600" type="submit">
                            <i class="fas fa-bolt me-1"></i> Lancer l'affectation
                        </button>
                    </form>
                @endif
                <a class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50"
                   href="{{ route('admin.orientation.campagnes.resultats', $campagne) }}">
                    <i class="fas fa-chart-column me-1"></i> Resultats
                </a>
                <a class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50"
                   href="{{ route('admin.orientation.campagnes.index') }}">
                    <i class="fas fa-arrow-left me-1"></i> @lang('lang.actions.back')
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 grid gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:grid-cols-3">
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.crud.status')</p>
                <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $campagne->statut_badge['class'] }}">{{ $campagne->statut_badge['label'] }}</span>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.fields.date_ouverture')</p>
                <p class="mt-1 text-sm text-gray-700">{{ $campagne->date_ouverture->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.fields.date_fermeture')</p>
                <p class="mt-1 text-sm text-gray-700">{{ $campagne->date_fermeture->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.fields.nombre_max_choix')</p>
                <p class="mt-1 text-sm text-gray-700">{{ $campagne->nombre_max_choix }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.fields.date_publication_resultats')</p>
                <p class="mt-1 text-sm text-gray-700">{{ $campagne->date_publication_resultats?->format('d/m/Y H:i') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">Documents requis</p>
                <p class="mt-1 text-sm text-gray-700">
                    @if($campagne->cni_requis) CNI @endif
                    @if($campagne->releve_notes_requis) &middot; @lang('lang.fields.releve_notes_path') @endif
                    @if($campagne->diplome_requis) &middot; @lang('lang.fields.diplome_path') @endif
                    @if(!$campagne->cni_requis && !$campagne->releve_notes_requis && !$campagne->diplome_requis) - @endif
                </p>
            </div>
        </div>

        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">{{ __('lang.resources.offres_orientation') }}</h2>
            <a href="{{ route('admin.orientation.offres.create', ['campagne' => $campagne->uuid]) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600">
                <i class="fas fa-plus text-xs"></i> @lang('lang.actions.add')
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">@lang('lang.fields.filiere')</th>
                        <th class="px-6 py-3">@lang('lang.resources.institution')</th>
                        <th class="px-6 py-3">@lang('lang.fields.capacite')</th>
                        <th class="px-6 py-3">@lang('lang.fields.moyenne_minimale')</th>
                        <th class="px-6 py-3 text-right">@lang('lang.crud.actions')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($campagne->offres as $offre)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $offre->filiere->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $offre->filiere->institution->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $offre->capacite }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $offre->moyenne_minimale }}</td>
                            <td class="px-6 py-3 text-right">
                                <a class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary"
                                   href="{{ route('admin.orientation.offres.edit', $offre) }}">
                                    <i class="fas fa-pen-to-square me-1"></i>@lang('lang.actions.edit')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">@lang('lang.crud.no_data')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
