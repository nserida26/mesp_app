@php
    $adminResources = [
        'institutions' => 'Institutions',
        'filieres' => 'Filieres',
        'etudiants' => 'Etudiants',
        'inscriptions' => 'Inscriptions',
        'enseignants' => 'Enseignants',
        'affectations' => 'Affectations',
        'accreditations' => 'Accreditations',
        'calendriers' => 'Calendriers',
    ];
@endphp

<aside class="w-full shrink-0 border-b border-gray-200 bg-white p-4 md:min-h-[calc(100vh-73px)] md:w-72 md:border-b-0 md:border-r">
    <div class="mb-5">
        <div class="text-xs font-semibold uppercase tracking-wide text-gray-500">Administration</div>
        <div class="mt-1 text-sm font-semibold text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</div>
    </div>

    <nav class="space-y-5 text-sm">
        <div>
            <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">General</div>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" class="block rounded-md px-3 py-2 {{ request()->routeIs('dashboard') ? 'bg-primary-50 font-semibold text-primary-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    Tableau de bord
                </a>
                <a href="{{ route('admin.imports.index') }}" class="block rounded-md px-3 py-2 {{ request()->routeIs('admin.imports.*') ? 'bg-primary-50 font-semibold text-primary-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    Imports
                </a>
            </div>
        </div>

        <div>
            <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Modeles</div>
            <div class="space-y-1">
                @foreach ($adminResources as $key => $label)
                    <a href="{{ route('admin.resources.index', $key) }}" class="block rounded-md px-3 py-2 {{ request()->route('resource') === $key ? 'bg-primary-50 font-semibold text-primary-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div>
            <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Securite</div>
            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}" class="block rounded-md px-3 py-2 {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 font-semibold text-primary-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    Utilisateurs
                </a>
                <a href="{{ route('admin.roles.index') }}" class="block rounded-md px-3 py-2 {{ request()->routeIs('admin.roles.*') ? 'bg-primary-50 font-semibold text-primary-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    Roles
                </a>
                <a href="{{ route('admin.permissions.index') }}" class="block rounded-md px-3 py-2 {{ request()->routeIs('admin.permissions.*') ? 'bg-primary-50 font-semibold text-primary-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    Permissions
                </a>
                <a href="{{ route('admin.audit-logs') }}" class="block rounded-md px-3 py-2 {{ request()->routeIs('admin.audit-logs') ? 'bg-primary-50 font-semibold text-primary-700' : 'text-gray-700 hover:bg-gray-50' }}">
                    Journal d'audit
                </a>
            </div>
        </div>
    </nav>
</aside>
