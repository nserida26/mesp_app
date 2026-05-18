@extends('layouts.app')

@section('title', $config['singular'])

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $config['singular'] }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $config['label'] }}</p>
            </div>
            <div class="flex gap-2">
                <a class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700" href="{{ route('admin.resources.index', $resource) }}">Retour</a>
                <a class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-600" href="{{ route('admin.resources.edit', [$resource, $item->uuid ?? $item->id]) }}">Modifier</a>
            </div>
        </div>

        <dl class="grid gap-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm md:grid-cols-2">
            @foreach ($item->getAttributes() as $key => $value)
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ str($key)->replace('_', ' ')->title() }}</dt>
                    <dd class="mt-1 break-words text-sm text-gray-900">
                        @if ($value instanceof \Carbon\CarbonInterface)
                            {{ $value->format('d/m/Y') }}
                        @else
                            {{ is_scalar($value) ? $value : json_encode($value) }}
                        @endif
                    </dd>
                </div>
            @endforeach
        </dl>
    </section>
</div>
@endsection
