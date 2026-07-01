@extends('layouts.app')

@section('title', $item ? __('lang.crud.edit', ['resource' => __($config['singular'])]) : __('lang.crud.create', ['resource' => __($config['singular'])]))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $item ? __('lang.crud.edit', ['resource' => __($config['singular'])]) : __('lang.crud.create', ['resource' => __($config['singular'])]) }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ __($config['label']) }}</p>
            </div>
            <a class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50"
               href="{{ route('admin.resources.index', $resource) }}">
                <i class="fas fa-arrow-left me-1"></i> @lang('lang.actions.back')
            </a>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ $item ? route('admin.resources.update', [$resource, $item->uuid ?? $item->id]) : route('admin.resources.store', $resource) }}" enctype="multipart/form-data" class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            @csrf
            @if ($item)
                @method('PUT')
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($config['fields'] as $name => $field)
                    @continue(!$item && ($field['readonly_on_create'] ?? false))
                    @php
                        $type = $field['type'] ?? 'text';
                        $label = isset($field['label']) ? __($field['label']) : __('lang.fields.' . $name);
                        if ($label === 'lang.fields.' . $name) {
                            $label = str($name)->replace('_', ' ')->title();
                        }
                        $value = ($item && ($field['write_only'] ?? false)) ? old($name, '') : old($name, $item->{$name} ?? '');
                        if ($value instanceof \Carbon\CarbonInterface) {
                            $value = $value->format('Y-m-d');
                        }
                    @endphp

                    <label class="{{ $type === 'textarea' ? 'md:col-span-2' : '' }} block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">{{ $label }} @if($field['required'] ?? false)<span class="text-red-600">*</span>@endif</span>

                        @if ($type === 'textarea')
                            <textarea name="{{ $name }}" rows="3" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">{{ $value }}</textarea>
                        @elseif ($type === 'select')
                            <select name="{{ $name }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                                <option value="">@lang('lang.actions.choose')</option>
                                @foreach (($field['options'] ?? []) as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ str_starts_with($optionLabel, 'lang.') ? __($optionLabel) : $optionLabel }}</option>
                                @endforeach
                            </select>
                        @elseif ($type === 'relation')
                            <select name="{{ $name }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                                <option value="">@lang('lang.actions.choose')</option>
                                @foreach (($options[$name] ?? []) as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @elseif ($type === 'file')
                            <input type="file" name="{{ $name }}" accept="{{ $field['accept'] ?? null }}" class="block w-full rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-3 text-sm file:me-4 file:rounded-lg file:border-0 file:bg-primary file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:border-primary focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                            @if($item && $item->{$name})
                                <a href="{{ Storage::url($item->{$name}) }}" target="_blank" class="mt-2 inline-flex items-center gap-2 text-xs font-semibold text-primary hover:underline">
                                    <i class="fas fa-paperclip"></i>
                                    @lang('lang.actions.view_current_file')
                                </a>
                            @endif
                        @else
                            <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" step="{{ $field['step'] ?? null }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                        @endif

                        @error($name)
                            <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                @endforeach
            </div>

            <div class="mt-6 flex gap-3">
                <button class="rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-600" type="submit">@lang('lang.actions.save')</button>
                <a class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50" href="{{ route('admin.resources.index', $resource) }}">@lang('lang.actions.cancel')</a>
            </div>
        </form>
    </section>
</div>
@endsection
