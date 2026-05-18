@extends('layouts.app')

@section('title', __('lang.admin.permission'))

@section('content')
    <form method="POST" action="{{ $permission ? route('admin.permissions.update', $permission) : route('admin.permissions.store') }}" class="max-w-xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @if ($permission) @method('PUT') @endif
        <label class="mb-5 block">
            <span class="mb-1 block text-sm font-medium">Nom</span>
            <input name="name" value="{{ old('name', $permission->name ?? '') }}" required class="w-full rounded-md border border-gray-300 px-3 py-2">
        </label>
        <button class="rounded-md bg-primary px-5 py-2 font-semibold text-white" type="submit">@lang('lang.actions.save')</button>
    </form>
@endsection
