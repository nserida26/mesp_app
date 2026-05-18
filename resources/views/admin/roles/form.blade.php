@extends('layouts.app')

@section('title', __('lang.admin.role'))

@section('content')
    <form method="POST" action="{{ $role ? route('admin.roles.update', $role) : route('admin.roles.store') }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @if ($role) @method('PUT') @endif
        <label class="mb-5 block">
            <span class="mb-1 block text-sm font-medium">Nom</span>
            <input name="name" value="{{ old('name', $role->name ?? '') }}" required class="w-full rounded-md border border-gray-300 px-3 py-2">
        </label>
        <div class="mb-5 grid gap-3 md:grid-cols-3">
            @foreach ($permissions as $permission)
                <label class="flex items-center gap-2 rounded-md border border-gray-200 px-3 py-2 text-sm">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($role?->hasPermissionTo($permission->name))>
                    <span>{{ $permission->name }}</span>
                </label>
            @endforeach
        </div>
        <button class="rounded-md bg-primary px-5 py-2 font-semibold text-white" type="submit">@lang('lang.actions.save')</button>
    </form>
@endsection
