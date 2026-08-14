@extends('layouts.public')

@section('title', $type === 'bac-licence' ? __('lang.orientation.form_title_bac') : __('lang.orientation.form_title_licence'))

@section('content')
<section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-center text-3xl font-black text-gray-900">
            {{ $type === 'bac-licence' ? __('lang.orientation.form_title_bac') : __('lang.orientation.form_title_licence') }}
        </h1>

        @if(!$campagne)
            <div class="mt-8 rounded-2xl border border-yellow-200 bg-yellow-50 p-6 text-center text-yellow-800">
                <i class="fas fa-circle-exclamation mb-2 block text-2xl"></i>
                @lang('lang.orientation.no_active_campaign')
            </div>
        @else
            @if(session('error'))
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('public.orientation.formulaire.store', $type) }}" enctype="multipart/form-data"
                  class="mt-6 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
                @csrf

                <div class="grid gap-4 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_nni') *</span>
                        <input name="nni" value="{{ old('nni') }}" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </label>

                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_full_name') *</span>
                        <input name="nom_complet" value="{{ old('nom_complet') }}" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </label>

                    @if($type === 'bac-licence')
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_type_bac') *</span>
                            <select name="type_bac_id" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">@lang('lang.actions.choose')</option>
                                @foreach($typesBac as $t)
                                    <option value="{{ $t->id }}" @selected((string) old('type_bac_id') === (string) $t->id)>{{ $t->libelle }}</option>
                                @endforeach
                            </select>
                        </label>
                    @else
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_domaine_licence') *</span>
                            <select name="domaine_licence_id" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">@lang('lang.actions.choose')</option>
                                @foreach($domainesLicence as $d)
                                    <option value="{{ $d->id }}" @selected((string) old('domaine_licence_id') === (string) $d->id)>{{ $d->nom }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endif

                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_moyenne') *</span>
                        <input type="number" step="0.01" min="0" max="20" name="moyenne_generale" value="{{ old('moyenne_generale') }}" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </label>

                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_annee') *</span>
                        <select name="annee_obtention" required class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">@lang('lang.actions.choose')</option>
                            @for($annee = (int) date('Y'); $annee >= (int) date('Y') - 10; $annee--)
                                <option value="{{ $annee }}" @selected((string) old('annee_obtention') === (string) $annee)>{{ $annee }}</option>
                            @endfor
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_phone')</span>
                        <div class="flex">
                            <span class="inline-flex items-center rounded-l-xl border border-r-0 border-gray-200 bg-gray-50 px-3 text-sm text-gray-500">+222</span>
                            <input type="text" name="telephone_local" maxlength="8" placeholder="12345678"
                                   class="w-full rounded-r-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500"
                                   oninput="document.getElementById('telephone_full').value = this.value ? '+222' + this.value : '';">
                        </div>
                        <input type="hidden" id="telephone_full" name="telephone" value="{{ old('telephone') }}">
                    </label>

                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">@lang('lang.orientation.form_email')</span>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </label>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-3">
                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">
                            @lang('lang.orientation.form_cni') @if($campagne->cni_requis)*@endif
                        </span>
                        <input type="file" name="cni" accept=".pdf" class="w-full rounded-xl border border-dashed border-gray-300 bg-gray-50 px-3 py-2 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-1 block text-sm font-medium text-gray-700">
                            @lang('lang.orientation.form_releve_notes') @if($campagne->releve_notes_requis)*@endif
                        </span>
                        <input type="file" name="releve_notes" accept=".pdf" class="w-full rounded-xl border border-dashed border-gray-300 bg-gray-50 px-3 py-2 text-sm">
                    </label>
                    @if($type === 'licence-master')
                        <label class="block">
                            <span class="mb-1 block text-sm font-medium text-gray-700">
                                @lang('lang.orientation.form_diplome') @if($campagne->diplome_requis)*@endif
                            </span>
                            <input type="file" name="diplome" accept=".pdf" class="w-full rounded-xl border border-dashed border-gray-300 bg-gray-50 px-3 py-2 text-sm">
                        </label>
                    @endif
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full rounded-xl bg-green-700 px-6 py-3 font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-800 md:w-auto">
                        @lang('lang.orientation.form_submit')
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        @endif
    </div>
</section>
@endsection
