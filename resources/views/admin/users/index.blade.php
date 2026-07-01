@extends('layouts.app')

@section('title', __('lang.admin.users_title'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">@lang('lang.admin.users')</h1>
                <p class="mt-1 text-sm text-gray-500">@lang('lang.admin.users_help')</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-600">
                <i class="fas fa-plus text-xs"></i>
                @lang('lang.admin.new_user')
            </a>
        </div>

        <form method="GET" class="mb-4 grid gap-3 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm md:grid-cols-[1fr_220px_auto]">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.actions.search')</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 start-3 flex items-center text-gray-400">
                        <i class="fas fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="@lang('lang.admin.search_name_email')"
                           class="w-full rounded-xl border border-gray-300 py-2.5 ps-9 pe-4 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.admin.role')</label>
                <select name="role" class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                    <option value="">@lang('lang.admin.all_roles')</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-filter text-xs"></i> @lang('lang.actions.filter')
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-6 py-3">@lang('lang.fields.name')</th>
                        <th class="px-6 py-3">@lang('lang.fields.email')</th>
                        <th class="px-6 py-3">@lang('lang.fields.roles')</th>
                        <th class="px-6 py-3">@lang('lang.fields.institution')</th>
                        <th class="px-6 py-3 text-right">@lang('lang.crud.actions')</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary text-xs font-bold text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="rounded-full bg-primary-50 px-2.5 py-0.5 text-xs font-semibold text-primary-700">{{ $role }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-3 text-gray-600">{{ $user->institution?->nom ?? '—' }}</td>
                            <td class="px-6 py-3">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-primary hover:text-primary">
                                        <i class="fas fa-pen-to-square me-1"></i>@lang('lang.actions.edit')
                                    </a>
                                    @if(auth()->id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                              onsubmit="return confirm('{{ __('lang.admin.confirm_delete_user') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-lg border border-red-100 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">
                                                <i class="fas fa-trash me-1"></i>@lang('lang.actions.delete')
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-users mb-3 block text-4xl text-gray-200"></i>
                                @lang('lang.admin.no_user_found')
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
    </section>
</div>
@endsection
