@extends('layouts.app')

@section('title', ucfirst($resourceName))

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">{{ ucfirst($resourceName) }}</h1>
        <a class="rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700" href="{{ route($routeName . '.index') }}">@lang('lang.actions.back')</a>
    </div>

    <dl class="grid gap-4 rounded-lg border border-gray-200 bg-white p-6 shadow-sm md:grid-cols-2">
        @foreach ($item->getAttributes() as $key => $value)
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">{{ str($key)->replace('_', ' ')->title() }}</dt>
                <dd class="mt-1 break-words text-sm text-gray-900">{{ is_scalar($value) ? $value : json_encode($value) }}</dd>
            </div>
        @endforeach
    </dl>
@endsection
