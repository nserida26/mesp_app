@extends('layouts.app')

@section('title', __('lang.admin.permissions'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">@lang('lang.admin.permissions')</h1>
                <p class="mt-1 text-sm text-gray-500">@lang('lang.admin.permissions_help')</p>
            </div>
            <a href="{{ route('admin.permissions.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600">
                <i class="fas fa-plus text-xs"></i>
                @lang('lang.actions.add')
            </a>
        </div>

        <div class="grid gap-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($permissions as $permission)
                <a href="{{ route('admin.permissions.edit', $permission) }}"
                   class="flex items-center justify-between gap-2 rounded-xl border border-gray-100 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-sm transition hover:-translate-y-0.5 hover:border-primary hover:bg-primary-50 hover:text-primary">
                    <span class="truncate">{{ $permission->name }}</span>
                    <i class="fas fa-pen-to-square shrink-0 text-xs text-gray-300 group-hover:text-primary"></i>
                </a>
            @empty
                <div class="col-span-4 rounded-2xl border border-dashed border-gray-200 py-12 text-center text-gray-400">
                    <i class="fas fa-key mb-3 block text-4xl text-gray-200"></i>
                    @lang('lang.admin.no_permission_defined')
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $permissions->links() }}</div>
    </section>
</div>
@endsection
