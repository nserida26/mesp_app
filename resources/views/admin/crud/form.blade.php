@extends('layouts.app')

@section('title', $item ? 'Modifier ' . $config['singular'] : 'Creer ' . $config['singular'])

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 p-4 md:p-8">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $item ? 'Modifier' : 'Creer' }} {{ $config['singular'] }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $config['label'] }}</p>
            </div>
            <a class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700" href="{{ route('admin.resources.index', $resource) }}">Retour</a>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ $item ? route('admin.resources.update', [$resource, $item->uuid ?? $item->id]) : route('admin.resources.store', $resource) }}" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @if ($item)
                @method('PUT')
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($config['fields'] as $name => $field)
                    @continue(!$item && ($field['readonly_on_create'] ?? false))
                    @php
                        $type = $field['type'] ?? 'text';
                        $label = $field['label'] ?? str($name)->replace('_', ' ')->title();
                        $value = ($item && ($field['write_only'] ?? false)) ? old($name, '') : old($name, $item->{$name} ?? '');
                        if ($value instanceof \Carbon\CarbonInterface) {
                            $value = $value->format('Y-m-d');
                        }
                    @endphp

                    <label class="{{ $type === 'textarea' ? 'md:col-span-2' : '' }} block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">{{ $label }} @if($field['required'] ?? false)<span class="text-red-600">*</span>@endif</span>

                        @if ($type === 'textarea')
                            <textarea name="{{ $name }}" rows="3" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">{{ $value }}</textarea>
                        @elseif ($type === 'select')
                            <select name="{{ $name }}" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                                <option value="">Choisir...</option>
                                @foreach (($field['options'] ?? []) as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @elseif ($type === 'relation')
                            <select name="{{ $name }}" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                                <option value="">Choisir...</option>
                                @foreach (($options[$name] ?? []) as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}" step="{{ $field['step'] ?? null }}" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                        @endif

                        @error($name)
                            <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </label>
                @endforeach
            </div>

            <div class="mt-6 flex gap-3">
                <button class="rounded-md bg-primary px-5 py-2 text-sm font-semibold text-white hover:bg-primary-600" type="submit">Enregistrer</button>
                <a class="rounded-md border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-700" href="{{ route('admin.resources.index', $resource) }}">Annuler</a>
            </div>
        </form>
    </section>
</div>
@endsection
