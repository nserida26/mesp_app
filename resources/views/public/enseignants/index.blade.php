@extends('layouts.public')

@section('title', __('lang.teachers_public.title'))

@section('content')
    <section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-4 py-1.5 text-sm font-semibold text-green-700 shadow-sm">
                    <i class="fas fa-chalkboard-teacher text-teal-500"></i>
                    @lang('lang.teachers_public.title')
                </div>
                <h1 class="text-4xl font-black text-gray-900">@lang('lang.teachers_public.title')</h1>
                <p class="mt-4 text-lg leading-relaxed text-gray-600">@lang('lang.teachers_public.subtitle')</p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('public.enseignants') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('lang.actions.search')</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500"
                        placeholder="@lang('lang.teachers_public.search_placeholder')">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">@lang('lang.teachers_public.grade')</label>
                    <select name="grade"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">@lang('lang.teachers_public.all_grades')</option>
                        @foreach ($grades as $grade)
                            <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                                {{ __('lang.teachers_public.grade_names.' . $grade) }}
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

                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="flex-1 rounded-xl bg-green-700 px-4 py-2.5 font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-800">
                        <i class="fas fa-filter mr-2"></i>
                        @lang('lang.actions.filter')
                    </button>
                    <a href="{{ route('public.enseignants') }}"
                        class="rounded-xl border border-gray-200 px-4 py-2.5 text-gray-600 hover:bg-gray-50"
                        title="@lang('lang.actions.reset')">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2 text-green-600"></i>
                    @lang('lang.teachers_public.list_title')
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.auth.name')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.verification.nni_number')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.teachers_public.accreditation_number')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.teachers_public.grade')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.teachers_public.speciality')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.institution')
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @lang('lang.students_public.status')
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($enseignants as $enseignant)
                            @php($affectation = $enseignant->affectationsActuelles->first())
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $enseignant->prenom }} {{ $enseignant->nom }}</div>
                                    @if (!empty($enseignant->email))
                                        <div class="text-xs text-gray-500">{{ $enseignant->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    {{ $enseignant->numero_national }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                                    {{ $enseignant->numero_accreditation }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $enseignant->grade_badge['class'] }}">
                                        {{ $enseignant->grade_badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $enseignant->specialite }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $affectation?->institution?->nom }}</div>
                                    <div class="text-xs text-gray-500">{{ $affectation?->filiere?->nom }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $enseignant->statut_badge['class'] }}">
                                        {{ $enseignant->statut_badge['label'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-chalkboard-teacher text-4xl mb-3 text-gray-300"></i>
                                    <p>@lang('lang.teachers_public.no_teacher')</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $enseignants->links() }}
            </div>
        </div>
    </div>
@endsection
