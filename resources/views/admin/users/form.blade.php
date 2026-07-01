@extends('layouts.app')

@section('title', isset($user) ? __('lang.admin.edit_user') : __('lang.admin.new_user'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ isset($user) ? __('lang.admin.edit_user') : __('lang.admin.new_user') }}</h1>
                <p class="mt-1 text-sm text-gray-500">@lang('lang.admin.user_form_help')</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
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
              action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}"
              class="max-w-3xl rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            @csrf
            @if(isset($user)) @method('PUT') @endif

            <div class="grid gap-5 md:grid-cols-2">
                <div class="block">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">@lang('lang.admin.full_name') <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required
                        class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                    @error('name')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </div>

                <div class="block">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">@lang('lang.fields.email') <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                        class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                    @error('email')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">@lang('lang.fields.institution')</label>
                    <select name="institution_id"
                        class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                        <option value="">@lang('lang.admin.no_institution')</option>
                        @foreach($institutions as $institution)
                            <option value="{{ $institution->id }}"
                                @selected((string) old('institution_id', $user->institution_id ?? '') === (string) $institution->id)>
                                {{ $institution->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('institution_id')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </div>

                <fieldset class="md:col-span-2">
                    <legend class="mb-2 block text-sm font-medium text-gray-700">@lang('lang.admin.roles') <span class="text-red-500">*</span></legend>
                    <div class="grid gap-2 rounded-xl border border-gray-100 bg-gray-50/50 p-4 md:grid-cols-3">
                        @foreach($roles as $role)
                            <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-gray-100 px-3 py-2 text-sm hover:border-primary hover:bg-primary-50">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary"
                                    @checked((isset($user) && $user->hasRole($role->name)) || in_array($role->name, old('roles', []), true))>
                                <span class="text-gray-700">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </fieldset>

                <div class="block">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">
                        @lang('lang.auth.password') {{ isset($user) ? '(' . __('lang.admin.password_optional') . ')' : '*' }}
                    </label>
                    <input type="password" name="password" {{ !isset($user) ? 'required' : '' }}
                        class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                    @error('password')<span class="mt-1 block text-xs text-red-600">{{ $message }}</span>@enderror
                </div>

                <div class="block">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700">@lang('lang.admin.confirm_password')</label>
                    <input type="password" name="password_confirmation"
                        class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit"
                    class="rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-600">
                    @lang('lang.actions.save')
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    @lang('lang.actions.cancel')
                </a>
            </div>
        </form>
    </section>
</div>
@endsection
