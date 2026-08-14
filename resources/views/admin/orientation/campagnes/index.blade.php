@extends('layouts.app')

@section('title', __('lang.resources.campagnes_orientation'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('lang.resources.campagnes_orientation') }}</h1>
                <p class="mt-1 text-sm text-gray-500">@lang('lang.crud.manage_help')</p>
            </div>
            <a href="{{ route('admin.orientation.campagnes.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600">
                <i class="fas fa-plus text-xs"></i>
                @lang('lang.actions.add')
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">@lang('lang.fields.nom')</th>
                        <th class="px-6 py-3">@lang('lang.fields.type_orientation')</th>
                        <th class="px-6 py-3">@lang('lang.fields.annee_universitaire')</th>
                        <th class="px-6 py-3">@lang('lang.crud.status')</th>
                        <th class="px-6 py-3">Offres / Candidats</th>
                        <th class="px-6 py-3 text-right">@lang('lang.crud.actions')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($campagnes as $campagne)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="font-semibold text-gray-900">{{ $campagne->nom }}</div>
                            </td>
                            <td class="px-6 py-3 text-gray-600">
                                {{ $campagne->type_orientation === 'bac_licence' ? __('lang.orientation.path_bac_licence') : __('lang.orientation.path_licence_master') }}
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $campagne->annee_universitaire }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $campagne->statut_badge['class'] }}">
                                    {{ $campagne->statut_badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ $campagne->offres_count }} / {{ $campagne->candidats_count }}</td>
                            <td class="px-6 py-3">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary"
                                       href="{{ route('admin.orientation.campagnes.show', $campagne) }}">
                                        <i class="fas fa-eye me-1"></i>@lang('lang.actions.view')
                                    </a>
                                    <a class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary"
                                       href="{{ route('admin.orientation.campagnes.edit', $campagne) }}">
                                        <i class="fas fa-pen-to-square me-1"></i>@lang('lang.actions.edit')
                                    </a>
                                    @if($campagne->statut !== 'active')
                                        <form method="POST" action="{{ route('admin.orientation.campagnes.activer', $campagne) }}">
                                            @csrf
                                            <button class="rounded-lg border border-green-200 px-3 py-1.5 text-xs font-semibold text-green-700 hover:bg-green-50" type="submit">
                                                <i class="fas fa-play me-1"></i>Activer
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.orientation.campagnes.fermer', $campagne) }}">
                                            @csrf
                                            <button class="rounded-lg border border-red-100 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" type="submit">
                                                <i class="fas fa-stop me-1"></i>Fermer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox mb-3 block text-4xl text-gray-200"></i>
                                @lang('lang.crud.no_data')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $campagnes->links() }}</div>
    </section>
</div>
@endsection
