@extends('layouts.app')

@section('title', __('lang.admin.audit_logs'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900">@lang('lang.admin.audit_logs')</h1>
            <p class="mt-1 text-sm text-gray-500">@lang('lang.admin.audit_help')</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">@lang('lang.admin.user')</th>
                        <th class="px-6 py-3">@lang('lang.crud.actions')</th>
                        <th class="px-6 py-3">@lang('lang.crud.element')</th>
                        <th class="px-6 py-3">@lang('lang.fields.date')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-medium text-gray-900">{{ $log->causer?->name ?? '—' }}</td>
                            <td class="px-6 py-3">
                                <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                    {{ str_contains($log->description, 'supprim') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $log->description }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ class_basename($log->subject_type ?? '') }}</td>
                            <td class="px-6 py-3 text-gray-400">{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-inbox mb-3 block text-4xl text-gray-200"></i>
                                @lang('lang.admin.no_activity')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $logs->links() }}</div>
    </section>
</div>
@endsection
