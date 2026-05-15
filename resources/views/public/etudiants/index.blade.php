@extends('layouts.public')

@section('title', 'Statistiques des étudiants')

@section('content')
    <div class="bg-gradient-to-r from-green-600 to-green-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-3xl font-bold mb-4">
                    <i class="fas fa-users mr-3"></i>
                    Statistiques des étudiants
                </h1>
                <p class="text-xl text-green-100">
                    Données anonymisées des inscriptions actives dans l'enseignement supérieur privé
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ number_format($stats['total_actifs']) }}
                </div>
                <div class="text-gray-600 text-sm">
                    <i class="fas fa-user-graduate mr-1"></i>
                    Étudiants actifs
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ number_format($stats['total_licence']) }}
                </div>
                <div class="text-gray-600 text-sm">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    Niveau Licence
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ number_format($stats['total_master']) }}
                </div>
                <div class="text-gray-600 text-sm">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    Niveau Master
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ number_format($stats['total_doctorat']) }}
                </div>
                <div class="text-gray-600 text-sm">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    Niveau Doctorat
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('public.etudiants') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Année universitaire</label>
                    <select name="annee"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Toutes les années</option>
                        @foreach ($annees as $annee)
                            <option value="{{ $annee }}" {{ request('annee') == $annee ? 'selected' : '' }}>
                                {{ $annee }}-{{ $annee + 1 }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit"
                        class="flex-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrer
                    </button>
                    <a href="{{ route('public.etudiants') }}"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                        <i class="fas fa-greeno"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Note sur la protection des données -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-shield-alt text-yellow-600 text-xl mr-3 mt-1"></i>
                <div>
                    <h3 class="font-semibold text-yellow-800 mb-1">Protection des données personnelles</h3>
                    <p class="text-sm text-yellow-700">
                        Conformément à la réglementation, seules des statistiques anonymisées sont affichées.
                        Les noms, prénoms et autres informations personnelles des étudiants ne sont pas accessibles
                        publiquement.
                    </p>
                </div>
            </div>
        </div>

        <!-- Liste des inscriptions (anonymisée) -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-list mr-2 text-green-600"></i>
                    Inscriptions actives (données anonymisées)
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                N° Inscription
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Filière
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Institution
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Niveau
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Semestre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Année
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
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
                                    <span
                                        class="px-2 py-1 text-xs rounded-full 
                                @if ($inscription->filiere->niveau == 'licence') bg-green-100 text-green-800
                                @elseif($inscription->filiere->niveau == 'master') bg-green-100 text-green-800
                                @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($inscription->filiere->niveau) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    S{{ $inscription->semestre_courant }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $inscription->annee_universitaire }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ ucfirst($inscription->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                                    <p>Aucune inscription trouvée</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $inscriptions->withQueryString()->links() }}
            </div>
        </div>

        <!-- Note explicative -->
        <div class="mt-6 text-xs text-gray-500 text-center">
            <p>
                <i class="fas fa-info-circle mr-1"></i>
                Cette liste est mise à jour quotidiennement. Les données sont fournies à titre indicatif.
            </p>
        </div>
    </div>
@endsection
