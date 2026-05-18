@extends('layouts.public')

@section('title', 'Filières autorisées')

@section('content')
    <section class="bg-gradient-to-br from-white via-green-50/70 to-teal-50/60 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <div
                    class="mb-4 inline-flex items-center gap-2 rounded-full border border-green-200 bg-white/90 px-4 py-1.5 text-sm font-semibold text-green-700 shadow-sm">
                    <i class="fas fa-graduation-cap text-teal-500"></i>
                    @lang('lang.nav.formations')
                </div>
                <h1 class="text-4xl font-black text-gray-900">Filières autorisées</h1>
                <p class="mt-4 text-lg leading-relaxed text-gray-600">
                    Liste officielle des filières accréditées dans l'enseignement supérieur privé
                </p>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="rounded-2xl bg-green-700 p-6 text-center text-white shadow-md">
                <div class="mb-2 text-3xl font-black">{{ number_format($stats['total_filieres']) }}</div>
                <div class="text-sm text-green-50">
                    <i class="fas fa-book mr-1"></i>
                    Filières actives
                </div>
            </div>

            <div class="rounded-2xl bg-teal-500 p-6 text-center text-white shadow-md">
                <div class="mb-2 text-3xl font-black">{{ number_format($stats['total_institutions']) }}</div>
                <div class="text-sm text-teal-50">
                    <i class="fas fa-university mr-1"></i>
                    Institutions
                </div>
            </div>

            <div class="rounded-2xl bg-blue-500 p-6 text-center text-white shadow-md">
                <div class="mb-2 text-3xl font-black">{{ number_format($stats['capacite_totale']) }}</div>
                <div class="text-sm text-blue-50">
                    <i class="fas fa-chair mr-1"></i>
                    Capacité totale
                </div>
            </div>

            <div class="rounded-2xl bg-green-700 p-6 text-center text-white shadow-md">
                <div class="mb-2 text-3xl font-black">{{ number_format($stats['etudiants_inscrits']) }}</div>
                <div class="text-sm text-green-50">
                    <i class="fas fa-user-graduate mr-1"></i>
                    Étudiants inscrits
                </div>
            </div>
        </div>

        <div class="mb-8 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('public.filieres') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Rechercher</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou code..."
                            class="w-full rounded-xl border-gray-200 pl-10 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Niveau</label>
                    <select name="niveau" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Tous les niveaux</option>
                        @foreach ($niveaux as $niveau)
                            <option value="{{ $niveau }}" {{ request('niveau') == $niveau ? 'selected' : '' }}>
                                {{ ucfirst($niveau) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Institution</label>
                    <select name="institution" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Toutes les institutions</option>
                        @foreach ($institutions as $institution)
                            <option value="{{ $institution->uuid }}"
                                {{ request('institution') == $institution->uuid ? 'selected' : '' }}>
                                {{ $institution->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Ville</label>
                    <select name="ville" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Toutes les villes</option>
                        @foreach ($villes as $ville)
                            <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                {{ $ville }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2 md:col-span-4">
                    <button type="submit"
                        class="rounded-xl bg-green-700 px-6 py-2.5 font-semibold text-white shadow-lg shadow-green-100 transition hover:bg-green-800">
                        <i class="fas fa-filter mr-2"></i>
                        Appliquer les filtres
                    </button>
                    <a href="{{ route('public.filieres') }}"
                        class="rounded-xl border border-gray-200 px-6 py-2.5 text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-redo mr-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($filieres as $filiere)
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md">
                    <div class="p-6">
                        <div class="mb-3 flex items-start justify-between gap-3">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $filiere->nom }}</h3>
                            <span class="rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                {{ ucfirst($filiere->niveau) }}
                            </span>
                        </div>

                        <p class="mb-4 text-sm text-gray-500">
                            <i class="fas fa-university mr-1"></i>
                            {{ $filiere->institution->nom }}
                            <span class="mx-1">•</span>
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $filiere->institution->ville }}
                        </p>

                        <div class="mb-4 grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-xl bg-gray-50 p-3">
                                <label class="text-xs text-gray-500">Code</label>
                                <p class="font-mono text-gray-900">{{ $filiere->code_filiere }}</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-3">
                                <label class="text-xs text-gray-500">Durée</label>
                                <p class="text-gray-900">{{ $filiere->duree_semestres }} semestres</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-3">
                                <label class="text-xs text-gray-500">Capacité</label>
                                <p class="text-gray-900">{{ $filiere->capacite_accueil }} places</p>
                            </div>
                            <div class="rounded-xl bg-gray-50 p-3">
                                <label class="text-xs text-gray-500">Inscrits</label>
                                <p class="text-gray-900">{{ $filiere->inscriptions_actives_count }}</p>
                            </div>
                        </div>

                        @php
                            $tauxRemplissage =
                                $filiere->capacite_accueil > 0
                                    ? round(($filiere->inscriptions_actives_count / $filiere->capacite_accueil) * 100)
                                    : 0;
                        @endphp
                        <div class="mb-4">
                            <div class="mb-1 flex justify-between text-xs text-gray-500">
                                <span>Taux de remplissage</span>
                                <span>{{ $tauxRemplissage }}%</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-gray-200">
                                <div class="h-2 rounded-full {{ $tauxRemplissage >= 90 ? 'bg-teal-500' : 'bg-green-600' }}"
                                    style="width: {{ min($tauxRemplissage, 100) }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                Arrêté: {{ $filiere->date_arrete_autorisation->format('d/m/Y') }}
                            </span>

                            <a href="{{ route('public.filieres.show', $filiere->uuid) }}"
                                class="text-sm font-semibold text-green-700 hover:text-green-900">
                                Détails
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center">
                    <i class="fas fa-book mb-4 text-6xl text-gray-300"></i>
                    <p class="text-lg text-gray-500">Aucune filière trouvée</p>
                    <p class="mt-2 text-sm text-gray-400">Essayez de modifier vos critères de recherche</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $filieres->withQueryString()->links() }}
        </div>
    </div>
@endsection
