@extends('layouts.public')

@section('title', __('lang.students_public.title'))

@section('content')
    <section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-4 py-1.5 text-sm font-semibold text-green-700 shadow-sm">
                    <i class="fas fa-users text-teal-500"></i>
                    @lang('lang.students_public.title')
                </div>
                <h1 class="text-4xl font-black text-gray-900">@lang('lang.students_public.title')</h1>
                <p class="mt-4 text-lg leading-relaxed text-gray-600">@lang('lang.students_public.subtitle')</p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="rounded-2xl bg-green-700 p-6 text-center text-white shadow-md">
                <div class="mb-2 text-3xl font-black text-white">
                    {{ number_format($stats['total_actifs']) }}
                </div>
                <div class="text-sm text-green-50">
                    <i class="fas fa-user-graduate mr-1"></i>
                    @lang('lang.students_public.active_students')
                </div>
            </div>

            <div class="rounded-2xl bg-teal-500 p-6 text-center text-white shadow-md">
                <div class="mb-2 text-3xl font-black text-white">
                    {{ number_format($stats['total_licence']) }}
                </div>
                <div class="text-sm text-teal-50">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    @lang('lang.students_public.licence_level')
                </div>
            </div>

            <div class="rounded-2xl bg-blue-500 p-6 text-center text-white shadow-md">
                <div class="mb-2 text-3xl font-black text-white">
                    {{ number_format($stats['total_master']) }}
                </div>
                <div class="text-sm text-blue-50">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    @lang('lang.students_public.master_level')
                </div>
            </div>

            <div class="rounded-2xl bg-green-700 p-6 text-center text-white shadow-md">
                <div class="mb-2 text-3xl font-black text-white">
                    {{ number_format($stats['total_doctorat']) }}
                </div>
                <div class="text-sm text-green-50">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    @lang('lang.students_public.doctorat_level')
                </div>
            </div>
        </div>

        <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('public.etudiants') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('lang.students_public.level')</label>
                    <select name="niveau"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">@lang('lang.students_public.all_levels')</option>
                        @foreach ($niveaux as $niveau)
                            <option value="{{ $niveau }}" {{ request('niveau') == $niveau ? 'selected' : '' }}>
                                {{ __('lang.students_public.level_names.' . $niveau) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('lang.students_public.institution')</label>
                    <select name="institution"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">@lang('lang.students_public.all_institutions')</option>
                        @foreach ($institutions as $institution)
                            <option value="{{ $institution->uuid }}"
                                {{ request('institution') == $institution->uuid ? 'selected' : '' }}>
                                {{ $institution->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('lang.students_public.academic_year')</label>
                    <select name="annee"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">@lang('lang.students_public.all_years')</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>
                                {{ $annee }}-{{ $annee + 1 }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="flex-1 rounded-xl bg-green-700 px-4 py-2.5 font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-800">
                        <i class="fas fa-filter mr-2"></i>
                        @lang('lang.actions.filter')
                    </button>
                    <a href="{{ route('public.etudiants') }}"
                        class="rounded-xl border border-gray-200 px-4 py-2.5 text-gray-600 hover:bg-gray-50"
                        title="@lang('lang.actions.reset')">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <div class="mb-6 rounded-2xl border border-yellow-200 bg-yellow-50 p-4">
            <div class="flex items-start">
                <i class="fas fa-shield-alt text-yellow-600 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-semibold text-yellow-800 mb-1">@lang('lang.students_public.privacy_title')</h3>
                    <p class="text-sm text-yellow-700">@lang('lang.students_public.privacy_text')</p>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2 text-green-600"></i>
                    @lang('lang.students_public.active_registrations')
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.registration_number')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.program')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.institution')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.level')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.semester')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.year')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.status')
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($inscriptions as $inscription)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    {{ $inscription->numero_inscription }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $inscription->filiere->nom }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $inscription->filiere->code_filiere }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $inscription->filiere->institution->nom }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $inscription->filiere->institution->ville }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        {{ __('lang.students_public.level_names.' . $inscription->filiere->niveau) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @lang('lang.students_public.semester_short'){{ $inscription->semestre_courant }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $inscription->annee_universitaire }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ __('lang.students_public.status_names.' . $inscription->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                                    <p>@lang('lang.students_public.no_registration')</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $inscriptions->withQueryString()->links() }}
            </div>
        </div>

        <div class="mt-6 text-xs text-gray-500 text-center">
            <p>
                <i class="fas fa-info-circle mr-1"></i>
                @lang('lang.students_public.notice')
            </p>
        </div>
    </div>
@endsection
