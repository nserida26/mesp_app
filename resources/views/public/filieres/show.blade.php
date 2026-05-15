@extends('layouts.public')

@section('title', $filiere->nom)

@section('content')
    <div class="bg-gradient-to-r from-green-600 to-green-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-white">
                <a href="{{ route('public.filieres') }}" class="text-green-100 hover:text-white mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux filières
                </a>
                <h1 class="text-3xl font-bold">{{ $filiere->nom }}</h1>
                <p class="text-green-100 mt-2">{{ $filiere->institution->nom }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Informations principales -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-green-600 mr-2"></i>
                        Informations générales
                    </h2>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Code filière</dt>
                            <dd class="font-mono text-gray-900">{{ $filiere->code_filiere }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Niveau</dt>
                            <dd>
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                @if ($filiere->niveau == 'licence') bg-green-100 text-green-800
                                @elseif($filiere->niveau == 'master') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($filiere->niveau) }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Durée des études</dt>
                            <dd class="text-gray-900">{{ $filiere->duree_semestres }} semestres
                                ({{ ceil($filiere->duree_semestres / 2) }} ans)</dd>
                        </div>

                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Capacité d'accueil</dt>
                            <dd class="text-gray-900">{{ $filiere->capacite_accueil }} étudiants</dd>
                        </div>

                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Étudiants inscrits</dt>
                            <dd class="text-gray-900">{{ $filiere->inscriptions_actives_count }}</dd>
                        </div>

                        <div>
                            <dt class="text-xs text-gray-500 uppercase">Places disponibles</dt>
                            <dd class="text-gray-900">
                                {{ max(0, $filiere->capacite_accueil - $filiere->inscriptions_actives_count) }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Institution -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-university text-green-600 mr-2"></i>
                        Établissement
                    </h3>

                    <div class="space-y-3">
                        <p class="font-semibold text-gray-900">{{ $filiere->institution->nom }}</p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $filiere->institution->adresse }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-city mr-2"></i>
                            {{ $filiere->institution->ville }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-phone mr-2"></i>
                            {{ $filiere->institution->telephone }}
                        </p>

                        <a href="{{ route('public.institutions.show', $filiere->institution->uuid) }}"
                            class="inline-block mt-3 text-green-600 hover:text-green-800 text-sm">
                            Voir l'établissement
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>

                <!-- Arrêté d'autorisation -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-file-contract text-green-600 mr-2"></i>
                        Autorisation ministérielle
                    </h3>

                    <dl class="space-y-3">
                        <div>
                            <dt class="text-xs text-gray-500">N° Arrêté</dt>
                            <dd class="font-mono text-gray-900">{{ $filiere->numero_arrete_autorisation }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Date d'autorisation</dt>
                            <dd class="text-gray-900">{{ $filiere->date_arrete_autorisation->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500">Statut</dt>
                            <dd>
                                <span class="text-green-600">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Statistiques détaillées -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                        Répartition des étudiants par semestre
                    </h2>

                    <div class="space-y-4">
                        @foreach ($statsParSemestre as $semestre => $count)
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-medium text-gray-700">Semestre {{ $semestre }}</span>
                                    <span class="text-gray-600">{{ $count }}
                                        étudiant{{ $count > 1 ? 's' : '' }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    @php
                                        $percentage =
                                            $filiere->inscriptions_actives_count > 0
                                                ? round(($count / $filiere->inscriptions_actives_count) * 100)
                                                : 0;
                                    @endphp
                                    <div class="h-3 rounded-full bg-green-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Graphique simple -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-4">Taux de remplissage global</h3>

                        @php
                            $tauxGlobal =
                                $filiere->capacite_accueil > 0
                                    ? round(($filiere->inscriptions_actives_count / $filiere->capacite_accueil) * 100)
                                    : 0;
                        @endphp

                        <div class="mb-2 flex justify-between">
                            <span class="text-sm text-gray-600">Occupation</span>
                            <span class="text-sm font-semibold">{{ $tauxGlobal }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="h-4 rounded-full 
                            @if ($tauxGlobal >= 90) bg-red-500
                            @elseif($tauxGlobal >= 70) bg-yellow-500
                            @else bg-green-500 @endif"
                                style="width: {{ $tauxGlobal }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $filiere->inscriptions_actives_count }} inscrits sur {{ $filiere->capacite_accueil }}
                            places
                        </p>
                    </div>
                </div>

                <!-- Filières similaires -->
                @if ($filieresSimilaires->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                        <h3 class="font-bold text-gray-900 mb-4">
                            <i class="fas fa-layer-group text-green-600 mr-2"></i>
                            Autres filières dans cet établissement
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($filieresSimilaires as $similaire)
                                <a href="{{ route('public.filieres.show', $similaire->uuid) }}"
                                    class="block p-4 border border-gray-200 rounded-lg hover:shadow-md transition">
                                    <h4 class="font-medium text-gray-900">{{ $similaire->nom }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full 
                                @if ($similaire->niveau == 'licence') bg-green-100 text-green-800
                                @elseif($similaire->niveau == 'master') bg-purple-100 text-purple-800
                                @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($similaire->niveau) }}
                                        </span>
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
