@extends('layouts.app')

@section('title', __('lang.resources.offres_orientation'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('lang.resources.offres_orientation') }}</h1>
                <p class="mt-1 text-sm text-gray-500">@lang('lang.crud.manage_help')</p>
            </div>
            <a href="{{ route('admin.orientation.offres.create') }}"
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

        <form method="GET" class="mb-4 flex gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
            <select name="campagne" class="w-full rounded-xl border border-gray-300 py-2.5 px-4 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100" onchange="this.form.submit()">
                <option value="">Toutes les campagnes</option>
                @foreach($campagnes as $c)
                    <option value="{{ $c->id }}" @selected((string) request('campagne') === (string) $c->id)>{{ $c->nom }} ({{ $c->annee_universitaire }})</option>
                @endforeach
            </select>
        </form>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">@lang('lang.fields.filiere')</th>
                        <th class="px-6 py-3">@lang('lang.resources.institution')</th>
                        <th class="px-6 py-3">@lang('lang.resources.campagne_orientation')</th>
                        <th class="px-6 py-3">@lang('lang.fields.capacite')</th>
                        <th class="px-6 py-3">@lang('lang.fields.moyenne_minimale')</th>
                        <th class="px-6 py-3 text-right">@lang('lang.crud.actions')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($offres as $offre)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-semibold text-gray-900">{{ $offre->filiere->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $offre->filiere->institution->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $offre->campagne->nom }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $offre->capacite }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $offre->moyenne_minimale }}</td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    <a class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary"
                                       href="{{ route('admin.orientation.offres.edit', $offre) }}">
                                        <i class="fas fa-pen-to-square me-1"></i>@lang('lang.actions.edit')
                                    </a>
                                    <form method="POST" action="{{ route('admin.orientation.offres.destroy', $offre) }}" onsubmit="return confirm('{{ __('lang.crud.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-lg border border-red-100 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50" type="submit">
                                            <i class="fas fa-trash me-1"></i>@lang('lang.actions.delete')
                                        </button>
                                    </form>
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

        <div class="mt-4">{{ $offres->links() }}</div>
    </section>
</div>
@endsection
