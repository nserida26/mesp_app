@extends('layouts.public')

@section('title', 'Accueil')

@section('content')
    <div class="bg-gradient-to-r from-green-600 to-green-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold mb-4">
                    Plateforme Nationale de l'Enseignement Supérieur Privé
                </h1>
                <p class="text-xl mb-8">
                    Vérifiez l'accréditation des institutions et l'authenticité des diplômes en temps réel
                </p>

                <!-- Zone de vérification rapide -->
                <div class="max-w-2xl mx-auto">
                    <form action="{{ route('public.verify.check') }}" method="POST" class="bg-white rounded-lg shadow-lg p-6">
                        @csrf
                        <div class="flex">
                            <input type="text" name="numero_bac" placeholder="Entrez le numéro de baccalauréat"
                                class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-gray-900"
                                required>
                            <button type="submit"
                                class="bg-green-600 text-white px-6 py-3 rounded-r-md hover:bg-green-700 transition">
                                <i class="fas fa-search mr-2"></i>Vérifier
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-lock text-green-600 mr-1"></i>
                            Vos données sont protégées - Vérification sécurisée
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <i class="fas fa-building text-4xl text-green-600 mb-3"></i>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['institutions'] }}</div>
                <div class="text-gray-600">Institutions Accréditées</div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <i class="fas fa-graduation-cap text-4xl text-green-600 mb-3"></i>
                <div class="text-3xl font-bold text-gray-900">{{ $stats['filieres'] }}</div>
                <div class="text-gray-600">Filières Autorisées</div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6 text-center">
                <i class="fas fa-users text-4xl text-green-600 mb-3"></i>
                <div class="text-3xl font-bold text-gray-900">{{ number_format($stats['etudiants']) }}</div>
                <div class="text-gray-600">Étudiants Inscrits</div>
            </div>
        </div>
    </div>

    <!-- Institutions récentes -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Institutions Récemment Accréditées</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($institutions_recentes as $institution)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            @if ($institution->logo_path)
                                <img src="{{ Storage::url($institution->logo_path) }}" alt="{{ $institution->nom }}"
                                    class="w-12 h-12 rounded-full mr-3">
                            @else
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-university text-green-600 text-xl"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $institution->nom }}</h3>
                                <p class="text-sm text-gray-500">{{ $institution->ville }}</p>
                            </div>
                        </div>

                        @if ($institution->accreditationActive)
                            <div class="text-sm text-green-600 mb-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                Accréditation valide jusqu'au
                                {{ $institution->accreditationActive->date_fin->format('d/m/Y') }}
                            </div>
                        @endif

                        <a href="{{ route('public.institutions.show', $institution->uuid) }}"
                            class="inline-block mt-4 text-green-600 hover:text-green-800">
                            Voir les détails <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
