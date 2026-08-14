@extends('layouts.app')

@section('title', $item ? __('lang.crud.edit', ['resource' => $resourceName]) : __('lang.crud.create', ['resource' => $resourceName]))

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $item ? __('lang.actions.edit') : __('lang.actions.add') }} {{ $resourceName }}</h1>
    </div>

    <form method="POST" action="{{ $item ? route($routeName . '.update', $item) : route($routeName . '.store') }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        @csrf
        @if ($item)
            @method('PUT')
        @endif

        <div class="grid gap-4 md:grid-cols-2">
            @foreach ($fields as $field)
                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">{{ str($field)->replace('_', ' ')->title() }}</span>
                    @if ($field === 'institution_id' && isset($userInstitution))
                        <input type="hidden" name="institution_id" value="{{ $userInstitution->id }}">
                        <input type="text" value="{{ $userInstitution->nom }}" disabled
                               class="block w-full rounded-md border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-500">
                    @elseif ($field === 'institution_id' && isset($institutions))
                        <select name="institution_id" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                            <option value="">--</option>
                            @foreach ($institutions as $institution)
                                <option value="{{ $institution->id }}" @selected((string) old($field, $item->{$field} ?? '') === (string) $institution->id)>
                                    {{ $institution->nom }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input
                            name="{{ $field }}"
                            value="{{ old($field, $item->{$field} ?? '') }}"
                            class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100"
                        >
                    @endif
                    @error($field)
                        <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </label>
            @endforeach
        </div>

        <div class="mt-6 flex gap-3">
            <button class="rounded-md bg-primary px-5 py-2 text-sm font-semibold text-white hover:bg-primary-600" type="submit">
                @lang('lang.actions.save')
            </button>
            <a class="rounded-md border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700" href="{{ route($routeName . '.index') }}">
                @lang('lang.actions.cancel')
            </a>
        </div>
    </form>
@endsection
