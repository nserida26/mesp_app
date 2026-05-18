@extends('layouts.app')

@section('title', __('lang.admin.roles'))

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">@lang('lang.admin.roles')</h1>
        <a href="{{ route('admin.roles.create') }}" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">@lang('lang.actions.add')</a>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        @foreach ($roles as $role)
            <div class="border-b border-gray-100 p-4 last:border-b-0">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="font-semibold">{{ $role->name }}</div>
                        <div class="mt-1 text-xs text-gray-500">{{ $role->permissions->pluck('name')->implode(', ') ?: __('lang.admin.no_permission') }}</div>
                    </div>
                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-sm font-semibold text-primary">@lang('lang.actions.edit')</a>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $roles->links() }}</div>
@endsection
