@extends('layouts.app')

@section('title', $offre ? __('lang.crud.edit', ['resource' => __('lang.resources.offre_orientation')]) : __('lang.crud.create', ['resource' => __('lang.resources.offre_orientation')]))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">
        <div class="mb-6 flex items-center justify-between gap-3 rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $offre ? __('lang.crud.edit', ['resource' => __('lang.resources.offre_orientation')]) : __('lang.crud.create', ['resource' => __('lang.resources.offre_orientation')]) }}
            </h1>
            <a class="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50"
               href="{{ $campagne ? route('admin.orientation.campagnes.show', $campagne) : route('admin.orientation.offres.index') }}">
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
              action="{{ $offre ? route('admin.orientation.offres.update', $offre) : route('admin.orientation.offres.store') }}"
              class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            @csrf
            @if($offre)
                @method('PUT')
            @endif

            @php $v = fn($f) => old($f, $offre->{$f} ?? ''); @endphp

            <div class="grid gap-4 md:grid-cols-2">
                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.resources.campagne_orientation') <span class="text-red-600">*</span></span>
                    <select id="campagne_select" name="campagne_orientation_id" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100" @disabled($offre)>
                        <option value="">@lang('lang.actions.choose')</option>
                        @foreach($campagnes as $c)
                            <option value="{{ $c->id }}" data-type="{{ $c->type_orientation }}"
                                    @selected((string) $v('campagne_orientation_id') === (string) $c->id || ($campagne && $campagne->id === $c->id))>
                                {{ $c->nom }} ({{ $c->annee_universitaire }})
                            </option>
                        @endforeach
                    </select>
                    @if($offre)<input type="hidden" name="campagne_orientation_id" value="{{ $offre->campagne_orientation_id }}">@endif
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.filiere') <span class="text-red-600">*</span></span>
                    <select name="filiere_id" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                        <option value="">@lang('lang.actions.choose')</option>
                        @foreach($filieres as $f)
                            <option value="{{ $f->id }}" data-niveau="{{ $f->niveau }}" @selected((string) $v('filiere_id') === (string) $f->id)>
                                {{ $f->nom }} &mdash; {{ $f->institution->nom }} ({{ $f->niveau }})
                            </option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.capacite') <span class="text-red-600">*</span></span>
                    <input type="number" name="capacite" value="{{ $v('capacite') }}" min="1" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.fields.moyenne_minimale') <span class="text-red-600">*</span></span>
                    <input type="number" step="0.01" min="0" max="20" name="moyenne_minimale" value="{{ $v('moyenne_minimale') }}" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                </label>

                <label class="block">
                    <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.crud.status') <span class="text-red-600">*</span></span>
                    <select name="statut" class="block w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-100">
                        <option value="active" @selected($v('statut') !== 'inactive')>Active</option>
                        <option value="inactive" @selected($v('statut') === 'inactive')>Inactive</option>
                    </select>
                </label>
            </div>

            @php
                $selectedTypesBac = old('types_bac', $offre?->typesBac->pluck('id')->all() ?? []);
                $selectedDomaines = old('domaines_licence', $offre?->domainesLicence->pluck('id')->all() ?? []);
            @endphp

            <div id="criteres_bac" class="mt-4 hidden">
                <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_type_bac') <span class="text-red-600">*</span></span>
                <div class="grid grid-cols-2 gap-2 rounded-xl border border-gray-200 p-4 md:grid-cols-3">
                    @foreach($typesBac as $t)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="types_bac[]" value="{{ $t->id }}" @checked(in_array($t->id, $selectedTypesBac))>
                            {{ $t->libelle }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div id="criteres_licence" class="mt-4 hidden">
                <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_domaine_licence') <span class="text-red-600">*</span></span>
                <div class="grid grid-cols-2 gap-2 rounded-xl border border-gray-200 p-4 md:grid-cols-3">
                    @foreach($domainesLicence as $d)
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="domaines_licence[]" value="{{ $d->id }}" @checked(in_array($d->id, $selectedDomaines))>
                            {{ $d->nom }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <button class="rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-600" type="submit">@lang('lang.actions.save')</button>
                <a class="rounded-xl border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50"
                   href="{{ $campagne ? route('admin.orientation.campagnes.show', $campagne) : route('admin.orientation.offres.index') }}">@lang('lang.actions.cancel')</a>
            </div>
        </form>
    </section>
</div>

<script>
    (function () {
        const select = document.getElementById('campagne_select');
        const bacBlock = document.getElementById('criteres_bac');
        const licenceBlock = document.getElementById('criteres_licence');

        function toggle() {
            const option = select.options[select.selectedIndex];
            const type = option ? option.dataset.type : null;
            bacBlock.classList.toggle('hidden', type !== 'bac_licence');
            licenceBlock.classList.toggle('hidden', type !== 'licence_master');
        }

        select.addEventListener('change', toggle);
        toggle();
    })();
</script>
@endsection
