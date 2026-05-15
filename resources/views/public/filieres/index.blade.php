@extends('layouts.public')

@section('title', 'Filières autorisées')

@section('content')
    <div class="bg-gradient-to-r from-green-600 to-green-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-3xl font-bold mb-4">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    Filières Autorisées
                </h1>
                <p class="text-xl text-green-100">
                    Liste officielle des filières accréditées dans l'enseignement supérieur privé
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ number_format($stats['total_filieres']) }}
                </div>
                <div class="text-gray-600 text-sm">
                    <i class="fas fa-book mr-1"></i>
                    Filières actives
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ number_format($stats['total_institutions']) }}
                </div>
                <div class="text-gray-600 text-sm">
                    <i class="fas fa-university mr-1"></i>
                    Institutions
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ number_format($stats['capacite_totale']) }}
                </div>
                <div class="text-gray-600 text-sm">
                    <i class="fas fa-chair mr-1"></i>
                    Capacité totale
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">
                    {{ number_format($stats['etudiants_inscrits']) }}
                </div>
                <div class="text-gray-600 text-sm">
                    <i class="fas fa-user-graduate mr-1"></i>
                    Étudiants inscrits
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('public.filieres') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou code..."
                            class="w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                    <select name="niveau"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Tous les niveaux</option>
                        @foreach ($niveaux as $niveau)
                            <option value="{{ $niveau }}" {{ request('niveau') == $niveau ? 'selected' : '' }}>
                                {{ ucfirst($niveau) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Institution</label>
                    <select name="institution"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                    <select name="ville"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Toutes les villes</option>
                        @foreach ($villes as $ville)
                            <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                {{ $ville }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-4 flex justify-end space-x-2">
                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-filter mr-2"></i>
                        Appliquer les filtres
                    </button>
                    <a href="{{ route('public.filieres') }}"
                        class="px-6 py-2 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-greeno mr-2"></i>
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Liste des filières -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($filieres as $filiere)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-semibold text-gray-900 text-lg">
                                {{ $filiere->nom }}
                            </h3>
                            <span
                                class="px-2 py-1 text-xs rounded-full 
                        @if ($filiere->niveau == 'licence') bg-green-100 text-green-800
                        @elseif($filiere->niveau == 'master') bg-green-100 text-green-800
                        @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($filiere->niveau) }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-university mr-1"></i>
                            {{ $filiere->institution->nom }}
                            <span class="mx-1">•</span>
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            {{ $filiere->institution->ville }}
                        </p>

                        <div class="grid grid-cols-2 gap-3 text-sm mb-4">
                            <div>
                                <label class="text-xs text-gray-500">Code</label>
                                <p class="font-mono text-gray-900">{{ $filiere->code_filiere }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Durée</label>
                                <p class="text-gray-900">{{ $filiere->duree_semestres }} semestres</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Capacité</label>
                                <p class="text-gray-900">{{ $filiere->capacite_accueil }} places</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Inscrits</label>
                                <p class="text-gray-900">{{ $filiere->inscriptions_actives_count }}</p>
                            </div>
                        </div>

                        <!-- Barre de progression capacité -->
                        @php
                            $tauxRemplissage =
                                $filiere->capacite_accueil > 0
                                    ? round(($filiere->inscriptions_actives_count / $filiere->capacite_accueil) * 100)
                                    : 0;
                        @endphp
                        <div class="mb-4">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>Taux de remplissage</span>
                                <span>{{ $tauxRemplissage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full 
                            @if ($tauxRemplissage >= 90) bg-green-500
                            @elseif($tauxRemplissage >= 70) bg-yellow-500
                            @else bg-green-500 @endif"
                                    style="width: {{ $tauxRemplissage }}%">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                Arrêté: {{ $filiere->date_arrete_autorisation->format('d/m/Y') }}
                            </span>

                            <a href="{{ route('public.filieres.show', $filiere->uuid) }}"
                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Détails
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <i class="fas fa-book text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500 text-lg">Aucune filière trouvée</p>
                    <p class="text-gray-400 text-sm mt-2">Essayez de modifier vos critères de recherche</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $filieres->withQueryString()->links() }}
        </div>
    </div>
@endsection
