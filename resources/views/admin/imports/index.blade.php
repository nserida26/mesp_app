@extends('layouts.app')

@section('title', __('lang.admin.imports'))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold">@lang('lang.admin.import_data')</h1>
        <p class="mt-1 text-sm text-gray-500">@lang('lang.admin.import_help')</p>
    </div>

    <form method="POST" action="{{ route('admin.imports.store') }}" enctype="multipart/form-data" class="max-w-2xl rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        <label class="mb-4 block">
            <span class="mb-1 block text-sm font-medium">@lang('lang.admin.target_table')</span>
            <select name="table" class="w-full rounded-md border border-gray-300 px-3 py-2">
                @foreach ($tables as $table)
                    <option value="{{ $table }}">{{ $table }}</option>
                @endforeach
            </select>
        </label>
        <label class="mb-5 block">
            <span class="mb-1 block text-sm font-medium">@lang('lang.admin.file')</span>
            <input name="file" type="file" required class="w-full rounded-md border border-gray-300 px-3 py-2">
        </label>
        <button class="rounded-md bg-primary px-5 py-2 font-semibold text-white" type="submit">@lang('lang.actions.import')</button>
    </form>
@endsection
