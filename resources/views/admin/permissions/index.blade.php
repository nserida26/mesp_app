@extends('layouts.app')

@section('title', __('lang.admin.permissions'))

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">@lang('lang.admin.permissions')</h1>
        <a href="{{ route('admin.permissions.create') }}" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">@lang('lang.actions.add')</a>
    </div>

    <div class="grid gap-2 md:grid-cols-3">
        @foreach ($permissions as $permission)
            <a href="{{ route('admin.permissions.edit', $permission) }}" class="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm hover:border-primary">
                {{ $permission->name }}
            </a>
        @endforeach
    </div>
    <div class="mt-4">{{ $permissions->links() }}</div>
@endsection
