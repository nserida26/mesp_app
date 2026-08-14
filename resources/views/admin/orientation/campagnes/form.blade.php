@extends('layouts.app')

@section('title', $campagne ? __('lang.crud.edit', ['resource' => __('lang.resources.campagne_orientation')]) : __('lang.crud.create', ['resource' => __('lang.resources.campagne_orientation')]))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $campagne ? __('lang.crud.edit', ['resource' => __('lang.resources.campagne_orientation')]) : __('lang.crud.create', ['resource' => __('lang.resources.campagne_orientation')]) }}
            </h1>
            <a class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50"
               href="{{ route('admin.orientation.campagnes.index') }}">
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

        <form method="POST"
              action="{{ $campagne ? route('admin.orientation.campagnes.update', $campagne) : route('admin.orientation.campagnes.store') }}"
              class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            @csrf
            @if($campagne)
                @method('PUT')
            @endif

            @php $v = fn($f) => old($f, $campagne->{$f} ?? ''); @endphp

            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.type_orientation') <span class="text-red-600">*</span></span>
                    <select name="type_orientation" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100" @disabled($campagne)>
                        <option value="bac_licence" @selected($v('type_orientation') === 'bac_licence')>@lang('lang.orientation.path_bac_licence')</option>
                        <option value="licence_master" @selected($v('type_orientation') === 'licence_master')>@lang('lang.orientation.path_licence_master')</option>
                    </select>
                    @if($campagne)<input type="hidden" name="type_orientation" value="{{ $campagne->type_orientation }}">@endif
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.nom') <span class="text-red-600">*</span></span>
                    <input name="nom" value="{{ $v('nom') }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.annee_universitaire') <span class="text-red-600">*</span></span>
                    <input type="number" name="annee_universitaire" value="{{ $v('annee_universitaire') }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.nombre_max_choix') <span class="text-red-600">*</span></span>
                    <input type="number" name="nombre_max_choix" value="{{ $v('nombre_max_choix') ?: 5 }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.date_ouverture') <span class="text-red-600">*</span></span>
                    <input type="datetime-local" name="date_ouverture" value="{{ $campagne?->date_ouverture?->format('Y-m-d\TH:i') ?? old('date_ouverture') }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.date_fermeture') <span class="text-red-600">*</span></span>
                    <input type="datetime-local" name="date_fermeture" value="{{ $campagne?->date_fermeture?->format('Y-m-d\TH:i') ?? old('date_fermeture') }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.date_publication_resultats')</span>
                    <input type="datetime-local" name="date_publication_resultats" value="{{ $campagne?->date_publication_resultats?->format('Y-m-d\TH:i') ?? old('date_publication_resultats') }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </label>
            </div>

            <div class="mt-4 grid gap-3 md:grid-cols-3">
                <label class="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-3">
                    <input type="checkbox" name="cni_requis" value="1" @checked($v('cni_requis'))>
                    <span class="text-sm text-gray-700">@lang('lang.fields.cni_requis')</span>
                </label>
                <label class="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-3">
                    <input type="checkbox" name="releve_notes_requis" value="1" @checked($v('releve_notes_requis'))>
                    <span class="text-sm text-gray-700">@lang('lang.fields.releve_notes_requis')</span>
                </label>
                <label class="flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-3">
                    <input type="checkbox" name="diplome_requis" value="1" @checked($v('diplome_requis'))>
                    <span class="text-sm text-gray-700">@lang('lang.fields.diplome_requis')</span>
                </label>
            </div>

            <div class="mt-6 flex gap-3">
                <button class="rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-600" type="submit">@lang('lang.actions.save')</button>
                <a class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50" href="{{ route('admin.orientation.campagnes.index') }}">@lang('lang.actions.cancel')</a>
            </div>
        </form>
    </section>
</div>
@endsection
