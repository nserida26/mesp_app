@extends('layouts.app')

@section('title', __('lang.admin.permission'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ isset($permission) ? __('lang.actions.edit') : __('lang.actions.add') }} — @lang('lang.admin.permission')
                </h1>
            </div>
            <a href="{{ route('admin.permissions.index') }}"
               class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                <i class="fas fa-arrow-left me-1"></i> @lang('lang.actions.back')
            </a>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
            </div>
        @endif

        <form method="POST"
              action="{{ isset($permission) ? route('admin.permissions.update', $permission) : route('admin.permissions.store') }}"
              class="max-w-lg rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            @csrf
            @if (isset($permission)) @method('PUT') @endif

            <div class="mb-6">
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    @lang('lang.admin.permission_name') <span class="text-red-500">*</span>
                </label>
                <input name="name"
                       value="{{ old('name', $permission->name ?? '') }}"
                       required
                       placeholder="@lang('lang.admin.permission_placeholder')"
                       class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                @error('name')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                <p class="mt-1.5 text-xs text-gray-400">@lang('lang.admin.permission_convention') (ex: <code class="rounded bg-gray-100 px-1">create etudiants</code>, <code class="rounded bg-gray-100 px-1">delete institutions</code>)</p>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-600">
                    @lang('lang.actions.save')
                </button>
                <a href="{{ route('admin.permissions.index') }}"
                   class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    @lang('lang.actions.cancel')
                </a>
            </div>
        </form>
    </section>
</div>
@endsection
