@extends('layouts.app')

@section('title', __('nav.dashboard') . ' — ' . config('app.name'))

@section('content')

<div class="layout-dashboard">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
        <div class="sidebar-section">{{ __('nav.dashboard') }}</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> {{ __('nav.dashboard') }}
                </a>
            </li>
        </ul>

        @can('view institutions')
        <div class="sidebar-section">{{ __('nav.formation') }}</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('institutions.index') }}" class="{{ request()->routeIs('institutions.*') ? 'active' : '' }}">
                    <i class="bi bi-building"></i> {{ __('home.mod_institutions_title') }}
                </a>
            </li>
            <li>
                <a href="{{ route('filieres.index') }}" class="{{ request()->routeIs('filieres.*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i> {{ __('home.mod_filieres_title') }}
                </a>
            </li>
            <li>
                <a href="{{ route('accreditations.index') }}" class="{{ request()->routeIs('accreditations.*') ? 'active' : '' }}">
                    <i class="bi bi-shield-check"></i> {{ __('home.mod_accreditation_title') }}
                </a>
            </li>
        </ul>
        @endcan

        @can('view etudiants')
        <div class="sidebar-section">{{ __('home.mod_etudiants_title') }}</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('etudiants.index') }}" class="{{ request()->routeIs('etudiants.*') ? 'active' : '' }}">
                    <i class="bi bi-person-vcard"></i> {{ __('home.mod_etudiants_title') }}
                </a>
            </li>
        </ul>
        @endcan

        @can('view enseignants')
        <div class="sidebar-section">{{ __('home.mod_enseignants_title') }}</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('enseignants.index') }}" class="{{ request()->routeIs('enseignants.*') ? 'active' : '' }}">
                    <i class="bi bi-person-workspace"></i> {{ __('home.mod_enseignants_title') }}
                </a>
            </li>
        </ul>
        @endcan

        @can('view calendrier')
        <div class="sidebar-section">{{ __('home.mod_calendrier_title') }}</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('calendrier.index') }}" class="{{ request()->routeIs('calendrier.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i> {{ __('home.mod_calendrier_title') }}
                </a>
            </li>
        </ul>
        @endcan

        @role('admin')
        <div class="sidebar-section">Administration</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Utilisateurs
                </a>
            </li>
            <li>
                <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                    <i class="bi bi-person-gear"></i> Rôles & permissions
                </a>
            </li>
            @can('view audit-logs')
            <li>
                <a href="{{ route('admin.audit-logs') }}" class="{{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Journal d'audit
                </a>
            </li>
            @endcan
        </ul>
        @endrole

        {{-- User info at bottom --}}
        <div style="padding:1rem 1.25rem; margin-top:2rem; border-top:1px solid var(--border);">
            <div style="display:flex; align-items:center; gap:.65rem;">
                <div style="width:36px;height:36px;border-radius:50%;background:var(--green-xlight);color:var(--green-dark);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;">
                    {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                </div>
                <div>
                    <div style="font-size:.85rem;font-weight:600;color:var(--text-primary);">{{ Str::limit(auth()->user()->name,20) }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);">
                        {{ auth()->user()->getRoleNames()->implode(', ') }}
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ── Main content ── --}}
    <div style="padding:2rem; overflow-x:auto;">

        {{-- Page header --}}
        <div class="d-flex justify-between align-center mb-3" style="flex-wrap:wrap; gap:1rem;">
            <div>
                <h1 style="font-size:1.5rem; margin-bottom:.2rem;">
                    {{ __('nav.dashboard') }}
                </h1>
                <p class="text-muted" style="font-size:.87rem;">
                    {{ __('home.hero_subtitle') }}
                </p>
            </div>
            <div class="d-flex gap-1" style="flex-wrap:wrap;">
                @can('export statistics')
                <a href="#" class="btn btn-outline btn-sm">
                    <i class="bi bi-download"></i> Export
                </a>
                @endcan
                <span class="badge badge-success" style="font-size:.78rem; padding:.3rem .75rem;">
                    {{ now()->translatedFormat('d M Y') }}
                </span>
            </div>
        </div>

        {{-- Stat cards --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:2rem;">
            @can('view institutions')
            <div class="stat-card">
                <div class="stat-card-icon"><i class="bi bi-building"></i></div>
                <div>
                    <div class="stat-card-value">{{ $stats['institutions'] ?? 0 }}</div>
                    <div class="stat-card-label">{{ __('home.stat_institutions') }}</div>
                </div>
            </div>
            @endcan
            @can('view etudiants')
            <div class="stat-card">
                <div class="stat-card-icon"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-card-value">{{ number_format($stats['etudiants'] ?? 0) }}</div>
                    <div class="stat-card-label">{{ __('home.stat_etudiants') }}</div>
                </div>
            </div>
            @endcan
            @can('view filieres')
            <div class="stat-card">
                <div class="stat-card-icon"><i class="bi bi-journal-bookmark"></i></div>
                <div>
                    <div class="stat-card-value">{{ $stats['filieres'] ?? 0 }}</div>
                    <div class="stat-card-label">{{ __('home.stat_filieres') }}</div>
                </div>
            </div>
            @endcan
            @can('view enseignants')
            <div class="stat-card">
                <div class="stat-card-icon"><i class="bi bi-person-badge"></i></div>
                <div>
                    <div class="stat-card-value">{{ $stats['enseignants'] ?? 0 }}</div>
                    <div class="stat-card-label">{{ __('home.stat_enseignants') }}</div>
                </div>
            </div>
            @endcan
        </div>

        {{-- Recent activity --}}
        @can('view audit-logs')
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history"></i>
                Activité récente
            </div>
            <div class="table-wrap" style="border:none; border-radius:0;">
                <table>
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Action</th>
                            <th>Entité</th>
                            <th>Date</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentLogs ?? [] as $log)
                        <tr>
                            <td>{{ $log->user->name ?? '—' }}</td>
                            <td><span class="badge badge-{{ $log->action === 'delete' ? 'danger' : 'success' }}">{{ $log->action }}</span></td>
                            <td>{{ $log->entity_type }} #{{ $log->entity_id }}</td>
                            <td>{{ $log->created_at->diffForHumans() }}</td>
                            <td style="font-family:monospace;font-size:.8rem;">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted" style="padding:2rem;">
                                <i class="bi bi-inbox" style="font-size:1.5rem; display:block; margin-bottom:.5rem;"></i>
                                Aucune activité récente
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endcan

    </div>
</div>

@endsection
