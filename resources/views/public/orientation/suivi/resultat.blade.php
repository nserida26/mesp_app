@extends('layouts.public')

@section('title', __('lang.orientation.track_title'))

@section('content')
<section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-center text-3xl font-black text-gray-900">{{ $candidat->nom_complet }}</h1>
        <p class="mt-1 text-center text-sm text-gray-500">{{ $candidat->campagne->nom }} &mdash; {{ $candidat->code_suivi }}</p>

        <div class="mt-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase text-gray-400">@lang('lang.crud.status')</p>
            <p class="mt-1 text-lg font-semibold text-gray-900">@lang('lang.orientation.status_' . $candidat->statut)</p>
        </div>

        @if($candidat->statut === 'soumise')
            <div class="mt-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-bold text-gray-900">@lang('lang.orientation.ranking_title')</h2>
                <div class="space-y-2">
                    @foreach($candidat->choix as $index => $c)
                        <div class="flex items-center gap-3 rounded-xl border border-gray-100 p-3 text-sm">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 font-bold text-gray-600">{{ $index + 1 }}</span>
                            <span class="text-gray-900">{{ $c->offre->filiere->nom }}</span>
                            <span class="text-gray-400">&mdash; {{ $c->offre->filiere->institution->nom }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-3 text-lg font-bold text-gray-900">@lang('lang.orientation.confirmation_title')</h2>

                @if(!$resultatsPublies)
                    <p class="text-gray-500">@lang('lang.orientation.result_pending')</p>
                @elseif($candidat->orientation && $candidat->orientation->statut === 'orientee')
                    <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                        <i class="fas fa-circle-check mr-1"></i>
                        {{ __('lang.orientation.result_orientee', ['formation' => $candidat->orientation->offre->filiere->nom . ' - ' . $candidat->orientation->offre->filiere->institution->nom]) }}
                    </div>
                @else
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">
                        @lang('lang.orientation.result_non_orientee')
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection
