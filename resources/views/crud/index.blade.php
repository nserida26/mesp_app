@extends('layouts.app')

@section('title', ucfirst($resourceName))

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ ucfirst($resourceName) }}</h1>
            <p class="mt-1 text-sm text-gray-500">@lang('lang.crud.manage_help')</p>
        </div>
        <a href="{{ route($routeName . '.create') }}" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-600">
            @lang('lang.actions.add')
        </a>
    </div>

    <form class="mb-4 flex gap-2" method="GET">
        <input name="search" value="{{ request('search') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm" placeholder="@lang('lang.crud.search_placeholder')">
        <button class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700" type="submit">@lang('lang.actions.filter')</button>
    </form>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">@lang('lang.crud.element')</th>
                    <th class="px-4 py-3">@lang('lang.crud.status')</th>
                    <th class="px-4 py-3 text-right">@lang('lang.crud.actions')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($items as $item)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900">
                                {{ $item->nom ?? $item->name ?? $item->numero_arrete ?? $item->annee_universitaire ?? $item->uuid }}
                            </div>
                            <div class="text-xs text-gray-500">{{ $item->email ?? $item->code_etablissement ?? $item->code_filiere ?? $item->created_at?->format('d/m/Y') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-primary-50 px-2 py-1 text-xs font-semibold text-primary-700">
                                {{ $item->statut ?? $item->role ?? 'actif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a class="text-primary hover:text-primary-600" href="{{ route($routeName . '.show', $item) }}">@lang('lang.actions.view')</a>
                            <a class="ml-3 text-gray-600 hover:text-gray-900" href="{{ route($routeName . '.edit', $item) }}">@lang('lang.actions.edit')</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-10 text-center text-gray-500">@lang('lang.crud.no_data')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
@endsection
