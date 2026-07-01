@extends('layouts.app')

@section('title', __('lang.nav.dashboard'))

@section('content')
<div class="md:flex">
    @include('partials.sidebar')

    <section class="min-w-0 flex-1 bg-[#F5F7FA] p-4 md:p-8">

        {{-- ── Page header ──────────────────────────────────────────── --}}
        <div class="mb-8 rounded-2xl border border-green-100 bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 p-5 shadow-sm md:p-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="mb-2 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-3 py-1 text-xs font-semibold text-green-700">
                    <i class="fas fa-landmark text-teal-500"></i>
                    @lang('lang.admin.admin_space')
                </p>
                <h1 class="text-2xl font-black text-gray-900 md:text-3xl">@lang('lang.nav.dashboard')</h1>
                <p class="mt-1 text-sm text-gray-500">{{ now()->translatedFormat('l d F Y') }}</p>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full border border-green-200 bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                <i class="fas fa-circle animate-pulse text-[8px] text-green-500"></i>
                @lang('lang.admin.system_operational')
            </span>
            </div>
        </div>

        {{-- ── Stat cards ───────────────────────────────────────────── --}}
        <div class="mb-8 grid grid-cols-2 gap-4 xl:grid-cols-4">

            @can('view institutions')
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">@lang('lang.resources.institutions')</p>
                        <p class="mt-2 text-3xl font-black text-gray-900">{{ number_format($stats['institutions'] ?? 0) }}</p>
                        <p class="mt-0.5 text-xs text-gray-400">@lang('lang.admin.stats.institutions_unit')</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-green-50">
                        <i class="fas fa-university text-green-600"></i>
                    </span>
                </div>
                <div class="mt-4 h-0.5 w-full overflow-hidden rounded-full bg-gray-100">
                    <div class="h-0.5 w-full rounded-full bg-green-500"></div>
                </div>
            </div>
            @endcan

            @can('view filieres')
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">@lang('lang.resources.filieres')</p>
                        <p class="mt-2 text-3xl font-black text-gray-900">{{ number_format($stats['filieres'] ?? 0) }}</p>
                        <p class="mt-0.5 text-xs text-gray-400">@lang('lang.admin.stats.filieres_unit')</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-50">
                        <i class="fas fa-graduation-cap text-teal-600"></i>
                    </span>
                </div>
                <div class="mt-4 h-0.5 w-full overflow-hidden rounded-full bg-gray-100">
                    <div class="h-0.5 w-4/5 rounded-full bg-teal-500"></div>
                </div>
            </div>
            @endcan

            @can('view enseignants')
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">@lang('lang.resources.enseignants')</p>
                        <p class="mt-2 text-3xl font-black text-gray-900">{{ number_format($stats['enseignants'] ?? 0) }}</p>
                        <p class="mt-0.5 text-xs text-gray-400">@lang('lang.admin.stats.enseignants_unit')</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-purple-50">
                        <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                    </span>
                </div>
                <div class="mt-4 h-0.5 w-full overflow-hidden rounded-full bg-gray-100">
                    <div class="h-0.5 w-2/3 rounded-full bg-purple-500"></div>
                </div>
            </div>
            @endcan

            @can('view etudiants')
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400">@lang('lang.resources.etudiants')</p>
                        <p class="mt-2 text-3xl font-black text-gray-900">{{ number_format($stats['etudiants'] ?? 0) }}</p>
                        <p class="mt-0.5 text-xs text-gray-400">@lang('lang.admin.stats.etudiants_unit')</p>
                    </div>
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50">
                        <i class="fas fa-user-graduate text-blue-600"></i>
                    </span>
                </div>
                <div class="mt-4 h-0.5 w-full overflow-hidden rounded-full bg-gray-100">
                    <div class="h-0.5 w-11/12 rounded-full bg-blue-500"></div>
                </div>
            </div>
            @endcan

        </div>

        {{-- ── Content grid ─────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Quick access (2/3) --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <h2 class="text-sm font-semibold text-gray-900">
                            <i class="fas fa-bolt me-2 text-yellow-400"></i>@lang('lang.admin.quick_access')
                        </h2>
                    </div>
                    <div class="grid grid-cols-2 gap-3 p-5 sm:grid-cols-3">
                        @foreach([
                            ['label'=>'lang.resources.institutions',   'icon'=>'fas fa-university',        'resource'=>'institutions',    'can'=>'view institutions'],
                            ['label'=>'lang.resources.filieres',       'icon'=>'fas fa-graduation-cap',     'resource'=>'filieres',        'can'=>'view filieres'],
                            ['label'=>'lang.resources.enseignants',    'icon'=>'fas fa-chalkboard-teacher', 'resource'=>'enseignants',     'can'=>'view enseignants'],
                            ['label'=>'lang.resources.etudiants',      'icon'=>'fas fa-user-graduate',      'resource'=>'etudiants',       'can'=>'view etudiants'],
                            ['label'=>'lang.resources.accreditations', 'icon'=>'fas fa-certificate',        'resource'=>'accreditations',  'can'=>'view accreditations'],
                            ['label'=>'lang.admin.import_export','icon'=>'fas fa-file-arrow-up',      'route'=>'admin.imports.index','can'=>null],
                        ] as $item)
                            @if($item['can'] && !auth()->user()->can($item['can'])) @continue @endif
                            <a href="{{ isset($item['route']) ? route($item['route']) : route('admin.resources.index', $item['resource']) }}"
                               class="group flex flex-col items-center gap-2.5 rounded-xl border border-gray-100 bg-gray-50/70 p-4 text-center transition hover:-translate-y-0.5 hover:border-primary/30 hover:bg-primary/5 hover:shadow-sm">
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-gray-100 transition group-hover:ring-primary/20">
                                    <i class="{{ $item['icon'] }} text-base text-gray-500 transition group-hover:text-primary"></i>
                                </span>
                                <span class="text-xs font-semibold text-gray-600 transition group-hover:text-primary">{{ __($item['label']) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Activity feed (1/3) --}}
            @can('view audit-logs')
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-gray-900">
                        <i class="fas fa-list-check me-2 text-primary"></i>@lang('lang.admin.recent_activity')
                    </h2>
                    <a href="{{ route('admin.audit-logs') }}" class="text-[11px] font-medium text-primary hover:underline">
                        @lang('lang.admin.view_all') <i class="fas fa-arrow-right ms-1 text-[9px]"></i>
                    </a>
                </div>

                <div class="divide-y divide-gray-50 p-2">
                    @forelse($recentLogs ?? [] as $log)
                    <div class="flex items-start gap-3 rounded-lg px-3 py-2.5 hover:bg-gray-50">
                        <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full
                            {{ str_contains($log->description, 'supprim') ? 'bg-red-50' : 'bg-green-50' }}">
                            <i class="fas {{ str_contains($log->description, 'supprim') ? 'fa-trash text-red-400' : 'fa-check text-green-500' }} text-[10px]"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-semibold text-gray-800">{{ $log->causer?->name ?? '—' }}</p>
                            <p class="text-[11px] text-gray-500">{{ $log->description }}</p>
                            <p class="mt-0.5 text-[10px] text-gray-400">{{ $log->created_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="py-10 text-center">
                        <i class="fas fa-inbox mb-2 block text-3xl text-gray-200"></i>
                        <p class="text-sm text-gray-400">@lang('lang.admin.no_activity')</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endcan

        </div>

    </section>
</div>
@endsection
