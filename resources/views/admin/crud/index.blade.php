@extends('layouts.app')

@section('title', __($config['label']))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __($config['label']) }}</h1>
                <p class="mt-1 text-sm text-gray-500">@lang('lang.crud.manage_help')</p>
            </div>
            <a href="{{ route('admin.resources.create', $resource) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600">
                <i class="fas fa-plus text-xs"></i>
                @lang('lang.actions.add')
            </a>
        </div>

        <form method="GET" class="mb-4 flex gap-2 rounded-2xl border border-gray-100 bg-white p-3 shadow-sm">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute inset-y-0 start-3 flex items-center text-gray-400">
                    <i class="fas fa-magnifying-glass text-xs"></i>
                </span>
                <input name="q" value="{{ request('q') }}"
                       class="w-full rounded-xl border border-gray-300 py-2.5 ps-9 pe-4 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100"
                       placeholder="@lang('lang.crud.search_placeholder')">
            </div>
            <button class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50" type="submit">
                <i class="fas fa-filter me-1 text-xs"></i>
                @lang('lang.actions.filter')
            </button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">@lang('lang.crud.element')</th>
                        <th class="px-6 py-3">@lang('lang.crud.status')</th>
                        <th class="px-6 py-3">@lang('lang.crud.created_at')</th>
                        <th class="px-6 py-3 text-right">@lang('lang.crud.actions')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($items as $item)
                        @php
                            $title = collect($config['title'])->map(fn ($field) => $item->{$field} ?? null)->filter()->implode(' - ');
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="font-semibold text-gray-900">{{ $title ?: ($item->uuid ?? $item->id) }}</div>
                                <div class="text-xs text-gray-400">#{{ $item->id ?? $item->uuid }}</div>
                            </td>
                            <td class="px-6 py-3">
                                <span class="rounded-full bg-primary-50 px-2.5 py-0.5 text-xs font-semibold text-primary-700">
                                    {{ $item->statut ?? $item->niveau ?? __('lang.public.active') }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    <a class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary"
                                       href="{{ route('admin.resources.show', [$resource, $item->uuid ?? $item->id]) }}">
                                        <i class="fas fa-eye me-1"></i>@lang('lang.actions.view')
                                    </a>
                                    <a class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary"
                                       href="{{ route('admin.resources.edit', [$resource, $item->uuid ?? $item->id]) }}">
                                        <i class="fas fa-pen-to-square me-1"></i>@lang('lang.actions.edit')
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.resources.destroy', [$resource, $item->uuid ?? $item->id]) }}"
                                          onsubmit="return confirm('{{ __('lang.crud.confirm_delete') }}')">
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
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox mb-3 block text-4xl text-gray-200"></i>
                                @lang('lang.crud.no_data')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $items->links() }}</div>
    </section>
</div>
@endsection
