@php
    $navGroups = [
        'lang.admin.groups.general' => [
            ['route' => 'dashboard', 'label' => 'lang.nav.dashboard', 'icon' => 'fas fa-chart-line', 'match' => 'dashboard'],
            ['route' => 'admin.imports.index', 'label' => 'lang.admin.import_export', 'icon' => 'fas fa-file-arrow-up', 'match' => 'admin.imports.*'],
        ],
        'lang.admin.groups.data' => [
            ['resource' => 'institutions', 'label' => 'lang.resources.institutions', 'icon' => 'fas fa-university', 'route_fallback' => 'institutions.index'],
            ['resource' => 'filieres', 'label' => 'lang.resources.filieres', 'icon' => 'fas fa-graduation-cap', 'route_fallback' => 'filieres.index'],
            ['resource' => 'maquettes', 'label' => 'lang.resources.maquettes', 'icon' => 'fas fa-layer-group', 'adminOnly' => true],
            ['resource' => 'etudiants', 'label' => 'lang.resources.etudiants', 'icon' => 'fas fa-user-graduate', 'route_fallback' => 'etudiants.index'],
            ['resource' => 'inscriptions', 'label' => 'lang.resources.inscriptions', 'icon' => 'fas fa-id-card', 'adminOnly' => true],
            ['resource' => 'enseignants', 'label' => 'lang.resources.enseignants', 'icon' => 'fas fa-chalkboard-teacher', 'route_fallback' => 'enseignants.index'],
            ['resource' => 'affectations', 'label' => 'lang.resources.affectations', 'icon' => 'fas fa-people-arrows', 'adminOnly' => true],
            ['resource' => 'accreditations', 'label' => 'lang.resources.accreditations', 'icon' => 'fas fa-certificate', 'route_fallback' => 'accreditations.index'],
            ['resource' => 'calendriers', 'label' => 'lang.resources.calendriers', 'icon' => 'fas fa-calendar-alt', 'route_fallback' => 'calendrier.index'],
        ],
        'lang.orientation.nav_label' => [
            ['route' => 'admin.orientation.campagnes.index', 'label' => 'lang.resources.campagnes_orientation', 'icon' => 'fas fa-route', 'match' => 'admin.orientation.campagnes.*', 'adminOnly' => true],
            ['route' => 'admin.orientation.offres.index', 'label' => 'lang.resources.offres_orientation', 'icon' => 'fas fa-list-check', 'match' => 'admin.orientation.offres.*', 'adminOnly' => true],
            ['route' => 'admin.orientation.candidats.index', 'label' => 'lang.resources.candidats_orientation', 'icon' => 'fas fa-user-clock', 'match' => 'admin.orientation.candidats.*', 'adminOnly' => true],
            ['resource' => 'types-bac', 'label' => 'lang.resources.types_bac', 'icon' => 'fas fa-tags', 'adminOnly' => true],
            ['resource' => 'domaines-licence', 'label' => 'lang.resources.domaines_licence', 'icon' => 'fas fa-tags', 'adminOnly' => true],
        ],
        'lang.admin.groups.security' => [
            ['route' => 'admin.validations.index', 'label' => 'lang.admin.validations', 'icon' => 'fas fa-check-double', 'match' => 'admin.validations.*'],
            ['route' => 'admin.users.index', 'label' => 'lang.admin.users', 'icon' => 'fas fa-users', 'match' => 'admin.users.*'],
            ['route' => 'admin.roles.index', 'label' => 'lang.admin.roles', 'icon' => 'fas fa-shield-alt', 'match' => 'admin.roles.*'],
            ['route' => 'admin.permissions.index', 'label' => 'lang.admin.permissions', 'icon' => 'fas fa-key', 'match' => 'admin.permissions.*'],
            ['route' => 'admin.audit-logs', 'label' => 'lang.admin.audit_logs', 'icon' => 'fas fa-list-check', 'match' => 'admin.audit-logs'],
        ],
    ];

    $isAdmin = auth()->user()->hasRole('admin');
@endphp

<aside class="w-full shrink-0 border-b border-gray-100 bg-white/95 md:sticky md:top-20 md:h-[calc(100vh-5rem)] md:w-72 md:overflow-y-auto md:border-b-0 md:border-e md:border-gray-100">
    <div class="border-b border-gray-100 p-4">
        <div class="flex items-center gap-3 rounded-2xl border border-green-100 bg-gradient-to-br from-green-50 to-white p-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-sm font-bold text-white shadow-sm">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="truncate text-xs font-medium text-primary">{{ auth()->user()->getRoleNames()->first() ?? 'admin' }}</p>
            </div>
        </div>
    </div>

    <nav class="p-4">
        @foreach ($navGroups as $groupLabel => $items)
            <div class="mb-5">
                <p class="mb-2 px-2 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                    {{ __($groupLabel) }}
                </p>

                <div class="space-y-1">
                    @foreach ($items as $item)
                        @continue (isset($item['resource']) && !$isAdmin && ($item['adminOnly'] ?? false))
                        @php
                            if (isset($item['resource'])) {
                                if (!$isAdmin && isset($item['route_fallback'])) {
                                    $isActive = request()->routeIs(str($item['route_fallback'])->before('.') . '.*');
                                    $href = route($item['route_fallback']);
                                } else {
                                    $isActive = request()->route('resource') === $item['resource'];
                                    $href = route('admin.resources.index', $item['resource']);
                                }
                            } else {
                                $isActive = request()->routeIs($item['match'] ?? $item['route']);
                                $href = route($item['route']);
                            }
                        @endphp

                        <a href="{{ $href }}"
                           class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition
                                  {{ $isActive ? 'bg-primary text-white shadow-sm shadow-green-100' : 'text-gray-600 hover:bg-primary-50 hover:text-primary' }}">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $isActive ? 'bg-white/15' : 'bg-gray-50 group-hover:bg-white' }}">
                                <i class="{{ $item['icon'] }} w-4 text-center text-xs {{ $isActive ? 'text-white/80' : 'text-gray-400 group-hover:text-primary' }}"></i>
                            </span>
                            {{ __($item['label']) }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
</aside>
