@extends('layouts.app')

@section('title', __('lang.admin.roles'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">@lang('lang.admin.roles')</h1>
                <p class="mt-1 text-sm text-gray-500">@lang('lang.admin.roles_help')</p>
            </div>
            <a href="{{ route('admin.roles.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600">
                <i class="fas fa-plus text-xs"></i>
                @lang('lang.actions.add')
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            @forelse ($roles as $role)
                <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-6 py-4 last:border-b-0 hover:bg-gray-50">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900">{{ $role->name }}</p>
                        <p class="mt-0.5 truncate text-xs text-gray-400">
                            {{ $role->permissions->pluck('name')->implode(' · ') ?: __('lang.admin.no_permission') }}
                        </p>
                    </div>
                    <a href="{{ route('admin.roles.edit', $role) }}"
                       class="shrink-0 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary">
                        <i class="fas fa-pen-to-square me-1"></i>
                        @lang('lang.actions.edit')
                    </a>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-400">
                    <i class="fas fa-shield-alt mb-3 block text-4xl text-gray-200"></i>
                    @lang('lang.admin.no_role_defined')
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $roles->links() }}</div>
    </section>
</div>
@endsection
