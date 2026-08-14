@extends('layouts.app')

@section('title', $candidat->nom_complet)

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $candidat->nom_complet }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $candidat->campagne->nom }}</p>
            </div>
            <a class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50"
               href="{{ route('admin.orientation.candidats.index') }}">
                <i class="fas fa-arrow-left me-1"></i> @lang('lang.actions.back')
            </a>
        </div>

        <div class="mb-6 grid gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:grid-cols-3">
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.fields.nni')</p>
                <p class="mt-1 text-sm text-gray-700">{{ $candidat->nni }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.fields.moyenne_generale')</p>
                <p class="mt-1 text-sm text-gray-700">{{ $candidat->moyenne_generale }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">
                    {{ $candidat->type_orientation === 'bac_licence' ? __('lang.orientation.form_type_bac') : __('lang.orientation.form_domaine_licence') }}
                </p>
                <p class="mt-1 text-sm text-gray-700">{{ $candidat->typeBac->libelle ?? $candidat->domaineLicence->nom ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.orientation.form_phone')</p>
                <p class="mt-1 text-sm text-gray-700">{{ $candidat->telephone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.orientation.form_email')</p>
                <p class="mt-1 text-sm text-gray-700">{{ $candidat->email ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.fields.code_suivi')</p>
                <p class="mt-1 font-mono text-sm text-gray-700">{{ $candidat->code_suivi ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.crud.status')</p>
                <p class="mt-1 text-sm text-gray-700">@lang('lang.orientation.status_' . $candidat->statut)</p>
            </div>
        </div>

        @if($candidat->cni_path || $candidat->releve_notes_path || $candidat->diplome_path)
            <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-3 text-lg font-bold text-gray-900">Documents</h2>
                <div class="flex flex-wrap gap-3">
                    @if($candidat->cni_path)
                        <a href="{{ Storage::url($candidat->cni_path) }}" target="_blank" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-primary hover:bg-gray-50">
                            <i class="fas fa-file-pdf me-1"></i> @lang('lang.fields.cni_path')
                        </a>
                    @endif
                    @if($candidat->releve_notes_path)
                        <a href="{{ Storage::url($candidat->releve_notes_path) }}" target="_blank" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-primary hover:bg-gray-50">
                            <i class="fas fa-file-pdf me-1"></i> @lang('lang.fields.releve_notes_path')
                        </a>
                    @endif
                    @if($candidat->diplome_path)
                        <a href="{{ Storage::url($candidat->diplome_path) }}" target="_blank" class="rounded-lg border border-gray-200 px-3 py-2 text-sm font-semibold text-primary hover:bg-gray-50">
                            <i class="fas fa-file-pdf me-1"></i> @lang('lang.fields.diplome_path')
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="mb-3 text-lg font-bold text-gray-900">@lang('lang.orientation.ranking_title')</h2>
            <div class="space-y-2">
                @forelse($candidat->choix as $index => $c)
                    <div class="flex items-center gap-3 rounded-xl border border-gray-100 p-3 text-sm">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 font-bold text-gray-600">{{ $index + 1 }}</span>
                        <span class="text-gray-900">{{ $c->offre->filiere->nom }}</span>
                        <span class="text-gray-400">&mdash; {{ $c->offre->filiere->institution->nom }}</span>
                    </div>
                @empty
                    <p class="text-gray-400">@lang('lang.orientation.no_choices_yet')</p>
                @endforelse
            </div>

            @if($candidat->orientation)
                <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                    @if($candidat->orientation->statut === 'orientee')
                        {{ __('lang.orientation.result_orientee', ['formation' => $candidat->orientation->offre->filiere->nom]) }}
                    @else
                        @lang('lang.orientation.result_non_orientee')
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
