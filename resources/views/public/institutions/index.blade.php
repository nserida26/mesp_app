@extends('layouts.public')

@section('title', __('lang.public.accredited_institutions'))

@section('content')
    <section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-4 py-1.5 text-sm font-semibold text-green-700 shadow-sm">
                    <i class="fas fa-university text-teal-500"></i>
                    @lang('lang.nav.institutions')
                </div>
                <h1 class="text-4xl font-black text-gray-900">
                    @lang('lang.public.accredited_institutions')
                </h1>
                <p class="mt-4 text-lg leading-relaxed text-gray-600">@lang('lang.public.official_list_institutions')</p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('public.institutions') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('lang.actions.search')</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="@lang('lang.public.institution_placeholder')"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('lang.public.city')</label>
                    <select name="ville"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">@lang('lang.public.all_cities')</option>
                        @foreach ($villes ?? [] as $ville)
                            <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                {{ $ville }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full rounded-xl bg-green-700 px-4 py-2.5 font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-800">
                        <i class="fas fa-filter mr-2"></i>
                        @lang('lang.actions.filter')
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($institutions as $institution)
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            @if ($institution->logo_path)
                                <img src="{{ Storage::url($institution->logo_path) }}" alt="{{ $institution->nom }}"
                                    class="w-16 h-16 rounded-full object-cover mr-4">
                            @else
                                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mr-4">
                                    <i class="fas fa-university text-green-600 text-2xl"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $institution->nom }}</h3>
                                <p class="text-sm text-gray-500">{{ $institution->ville }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-map-marker-alt text-gray-400 w-5 mr-2"></i>
                                <span class="text-gray-600">{{ $institution->adresse }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-phone text-gray-400 w-5 mr-2"></i>
                                <span class="text-gray-600">{{ $institution->telephone }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-envelope text-gray-400 w-5 mr-2"></i>
                                <span class="text-gray-600">{{ $institution->email }}</span>
                            </div>
                        </div>

                        @if ($institution->accreditationActive)
                            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-3">
                                <div class="flex items-center text-green-700 text-sm">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span>
                                        @lang('lang.public.valid_until')
                                        {{ $institution->accreditationActive->date_fin->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $institution->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $institution->statut === 'actif' ? __('lang.public.active') : __('lang.public.inactive') }}
                            </span>

                            <a href="{{ route('public.institutions.show', $institution->uuid) }}"
                                class="text-sm font-semibold text-green-700 hover:text-green-900">
                                @lang('lang.actions.details')
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <i class="fas fa-building text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500">@lang('lang.public.no_institution_found')</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $institutions->links() }}
        </div>
    </div>
@endsection
