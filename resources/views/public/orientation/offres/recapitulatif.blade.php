@extends('layouts.public')

@section('title', __('lang.orientation.ranking_title'))

@section('content')
<section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-3xl font-black text-gray-900">@lang('lang.orientation.ranking_title')</h1>
            <a href="{{ route('public.orientation.offres') }}" class="text-sm font-semibold text-green-700 hover:text-green-900">
                <i class="fas fa-arrow-left mr-1"></i> @lang('lang.actions.back')
            </a>
        </div>

        <p class="mb-6 text-sm text-gray-500">@lang('lang.orientation.ranking_help')</p>

        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif

        @if($choix->isEmpty())
            <div class="rounded-2xl border border-gray-100 bg-white p-10 text-center shadow-sm">
                <i class="fas fa-inbox mb-4 text-5xl text-gray-300"></i>
                <p class="text-gray-500">@lang('lang.orientation.no_choices_yet')</p>
                <a href="{{ route('public.orientation.offres') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-800">
                    @lang('lang.orientation.offers_title_licence')
                </a>
            </div>
        @else
            @php $offreIds = $choix->pluck('offre_orientation_id')->all(); @endphp
            <div class="space-y-3">
                @foreach($choix as $index => $c)
                    <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-700 font-bold text-white">
                            {{ $index + 1 }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate font-semibold text-gray-900">{{ $c->offre->filiere->nom }}</p>
                            <p class="truncate text-sm text-gray-500">{{ $c->offre->filiere->institution->nom }}</p>
                        </div>
                        <div class="flex shrink-0 gap-1">
                            @if($index > 0)
                                @php
                                    $swapped = $offreIds;
                                    [$swapped[$index - 1], $swapped[$index]] = [$swapped[$index], $swapped[$index - 1]];
                                @endphp
                                <form method="POST" action="{{ route('public.orientation.offres.reordonner') }}">
                                    @csrf
                                    @foreach($swapped as $id) <input type="hidden" name="offres[]" value="{{ $id }}"> @endforeach
                                    <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50" title="@lang('lang.actions.up')">
                                        <i class="fas fa-arrow-up"></i>
                                    </button>
                                </form>
                            @endif
                            @if($index < $choix->count() - 1)
                                @php
                                    $swapped = $offreIds;
                                    [$swapped[$index + 1], $swapped[$index]] = [$swapped[$index], $swapped[$index + 1]];
                                @endphp
                                <form method="POST" action="{{ route('public.orientation.offres.reordonner') }}">
                                    @csrf
                                    @foreach($swapped as $id) <input type="hidden" name="offres[]" value="{{ $id }}"> @endforeach
                                    <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50" title="@lang('lang.actions.down')">
                                        <i class="fas fa-arrow-down"></i>
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('public.orientation.offres.retirer', $c->offre->uuid) }}">
                                @csrf
                                @method('DELETE')
                                <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 text-red-600 hover:bg-red-50" title="@lang('lang.orientation.remove_offer')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 rounded-2xl border border-yellow-200 bg-yellow-50 p-5 text-sm text-yellow-800">
                <i class="fas fa-triangle-exclamation mr-1"></i>
                @lang('lang.orientation.validation_irreversible_warning')
            </div>

            <form method="POST" action="{{ route('public.orientation.valider') }}" class="mt-4"
                  onsubmit="return confirm('{{ __('lang.orientation.validate_confirm') }}')">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-green-700 px-6 py-3 font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-800">
                    <i class="fas fa-check mr-2"></i>
                    @lang('lang.orientation.validate_definitively')
                </button>
            </form>
        @endif
    </div>
</section>
@endsection
