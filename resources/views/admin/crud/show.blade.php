@extends('layouts.app')

@section('title', __($config['singular']))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __($config['singular']) }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ __($config['label']) }}</p>
            </div>
            <div class="flex gap-2">
                <a class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50"
                   href="{{ route('admin.resources.index', $resource) }}">
                    <i class="fas fa-arrow-left me-1"></i> @lang('lang.actions.back')
                </a>
                <a class="rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-600"
                   href="{{ route('admin.resources.edit', [$resource, $item->uuid ?? $item->id]) }}">
                    <i class="fas fa-pen-to-square me-1"></i> @lang('lang.actions.edit')
                </a>
            </div>
        </div>

        <dl class="grid gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:grid-cols-2">
            @foreach ($item->getAttributes() as $key => $value)
                <div>
                    @php
                        $label = __('lang.fields.' . $key);
                        if ($label === 'lang.fields.' . $key) {
                            $label = str($key)->replace('_', ' ')->title();
                        }
                    @endphp
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ $label }}</dt>
                    <dd class="mt-1 break-words text-sm text-gray-900">
                        @if ($value instanceof \Carbon\CarbonInterface)
                            {{ $value->format('d/m/Y') }}
                        @elseif (str_ends_with($key, '_path') && $value)
                            <a href="{{ Storage::url($value) }}" target="_blank" class="font-semibold text-primary hover:underline">
                                @lang('lang.actions.view_current_file')
                            </a>
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
