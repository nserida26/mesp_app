@extends('layouts.public')

@section('title', 'Statistiques publiques')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-12">
        <h1 class="mb-6 text-3xl font-bold text-gray-900">Statistiques publiques</h1>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $label => $value)
                <article class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">{{ str($label)->replace('_', ' ')->title() }}</p>
                    <p class="mt-2 text-3xl font-bold text-primary">{{ number_format($value) }}</p>
                </article>
            @endforeach
        </div>
    </section>
@endsection
