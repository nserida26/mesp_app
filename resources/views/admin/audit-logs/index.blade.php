@extends('layouts.app')

@section('title', 'Audit logs')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">@lang('lang.admin.audit_logs')</h1>
    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr><th class="px-4 py-3">Action</th><th class="px-4 py-3">Sujet</th><th class="px-4 py-3">Date</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($logs as $log)
                    <tr>
                        <td class="px-4 py-3">{{ $log->description }}</td>
                        <td class="px-4 py-3">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</td>
                        <td class="px-4 py-3">{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">@lang('lang.admin.no_activity')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
@endsection
