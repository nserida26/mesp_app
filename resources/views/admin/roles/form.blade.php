@extends('layouts.app')

@section('title', __('lang.admin.role'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $role ? __('lang.actions.edit') : __('lang.actions.add') }} — @lang('lang.admin.role')
                </h1>
            </div>
            <a href="{{ route('admin.roles.index') }}"
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
              action="{{ $role ? route('admin.roles.update', $role) : route('admin.roles.store') }}"
              class="max-w-3xl rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            @csrf
            @if ($role) @method('PUT') @endif

            <div class="mb-6">
                <label class="mb-1.5 block text-sm font-medium text-gray-700">
                    @lang('lang.admin.role_name') <span class="text-red-500">*</span>
                </label>
                <input name="name" value="{{ old('name', $role->name ?? '') }}" required
                    class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                @error('name')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
            </div>

            <div class="mb-6">
                <p class="mb-3 text-sm font-medium text-gray-700">@lang('lang.admin.associated_permissions')</p>
                <div class="grid gap-2 md:grid-cols-3">
                    @foreach ($permissions as $permission)
                        <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-gray-100 bg-gray-50/60 px-3 py-2 text-sm hover:border-primary hover:bg-primary-50">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                @checked($role?->hasPermissionTo($permission->name))>
                            <span class="text-gray-700">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-600">
                    @lang('lang.actions.save')
                </button>
                <a href="{{ route('admin.roles.index') }}"
                   class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    @lang('lang.actions.cancel')
                </a>
            </div>
        </form>
    </section>
</div>
@endsection
