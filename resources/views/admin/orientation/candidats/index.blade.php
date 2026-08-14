@extends('layouts.app')

@section('title', __('lang.resources.candidats_orientation'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('lang.resources.candidats_orientation') }}</h1>
        </div>

        <form method="GET" class="mb-4 grid gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm md:grid-cols-4">
            <input name="q" value="{{ request('q') }}" placeholder="@lang('lang.crud.search_placeholder')"
                   class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
            <select name="campagne" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                <option value="">Toutes les campagnes</option>
                @foreach($campagnes as $c)
                    <option value="{{ $c->id }}" @selected((string) request('campagne') === (string) $c->id)>{{ $c->nom }}</option>
                @endforeach
            </select>
            <select name="statut" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                <option value="">@lang('lang.crud.status')</option>
                <option value="brouillon" @selected(request('statut') === 'brouillon')>@lang('lang.orientation.status_brouillon')</option>
                <option value="soumise" @selected(request('statut') === 'soumise')>@lang('lang.orientation.status_soumise')</option>
            </select>
            <button class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50" type="submit">
                <i class="fas fa-filter me-1 text-xs"></i> @lang('lang.actions.filter')
            </button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">@lang('lang.orientation.form_full_name')</th>
                        <th class="px-6 py-3">@lang('lang.resources.campagne_orientation')</th>
                        <th class="px-6 py-3">@lang('lang.fields.moyenne_generale')</th>
                        <th class="px-6 py-3">@lang('lang.crud.status')</th>
                        <th class="px-6 py-3 text-right">@lang('lang.crud.actions')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($candidats as $candidat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $candidat->nom_complet }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $candidat->campagne->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $candidat->moyenne_generale }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full bg-primary-50 px-2.5 py-0.5 text-xs font-semibold text-primary-700">
                                    @lang('lang.orientation.status_' . $candidat->statut)
                                </span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                <a class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary"
                                   href="{{ route('admin.orientation.candidats.show', $candidat) }}">
                                    <i class="fas fa-eye me-1"></i>@lang('lang.actions.view')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox mb-3 block text-4xl text-gray-200"></i>
                                @lang('lang.crud.no_data')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $candidats->links() }}</div>
    </section>
</div>
@endsection
