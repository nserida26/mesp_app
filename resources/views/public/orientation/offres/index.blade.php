@extends('layouts.public')

@section('title', $candidat->type_orientation === 'bac_licence' ? __('lang.orientation.offers_title_licence') : __('lang.orientation.offers_title_master'))

@section('content')
<section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-3xl font-black text-gray-900">
                {{ $candidat->type_orientation === 'bac_licence' ? __('lang.orientation.offers_title_licence') : __('lang.orientation.offers_title_master') }}
            </h1>
            <a href="{{ route('public.orientation.recapitulatif') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-green-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-800">
                <i class="fas fa-list-check"></i>
                @lang('lang.orientation.ranking_title') ({{ count($offreIdsChoisies) }})
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($offres as $offre)
                @php $dejaChoisi = in_array($offre->id, $offreIdsChoisies); @endphp
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $offre->filiere->nom }}</h3>
                        <p class="mb-4 text-sm text-gray-500">
                            <i class="fas fa-university mr-1"></i>
                            {{ $offre->filiere->institution->nom }}
                            <span class="mx-1">&bull;</span>
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $offre->filiere->institution->ville }}
                        </p>

                        <div class="mb-4 grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-xl bg-gray-50 p-3">
                                <label class="text-xs text-gray-500">@lang('lang.orientation.duration')</label>
                                <p class="text-gray-900">{{ $offre->filiere->duree_annees }} an(s)</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-3">
                                <label class="text-xs text-gray-500">@lang('lang.orientation.places_available')</label>
                                <p class="text-gray-900">{{ $offre->capacite }}</p>
                            </div>
                        </div>

                        <p class="mb-4 inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                            <i class="fas fa-circle-check"></i>
                            @lang('lang.orientation.compatible_with')
                        </p>

                        <div class="border-t border-gray-100 pt-3">
                            @if($dejaChoisi)
                                <span class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-500">
                                    <i class="fas fa-check"></i> @lang('lang.orientation.already_selected')
                                </span>
                            @else
                                <form method="POST" action="{{ route('public.orientation.offres.choisir', $offre->uuid) }}">
                                    @csrf
                                    <button type="submit" class="w-full rounded-xl bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-800">
                                        <i class="fas fa-plus mr-1"></i>
                                        @lang('lang.orientation.select_offer')
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center">
                    <i class="fas fa-inbox mb-4 text-6xl text-gray-300"></i>
                    <p class="text-lg text-gray-500">@lang('lang.orientation.offers_empty')</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
