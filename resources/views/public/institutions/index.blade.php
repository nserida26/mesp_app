@extends('layouts.public')

@section('title', 'Institutions Accréditées')

@section('content')
    <div class="bg-gradient-to-r from-green-600 to-green-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-3xl font-bold mb-4">
                    <i class="fas fa-university mr-3"></i>
                    Institutions Accréditées
                </h1>
                <p class="text-xl">
                    Liste officielle des établissements d'enseignement supérieur privé autorisés
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('public.institutions') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nom de l'établissement..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                    <select name="ville"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Toutes les villes</option>
                        @foreach ($villes ?? [] as $ville)
                            <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                {{ $ville }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des institutions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($institutions as $institution)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            @if ($institution->logo_path)
                                <img src="{{ Storage::url($institution->logo_path) }}" alt="{{ $institution->nom }}"
                                    class="w-16 h-16 rounded-full object-cover mr-4">
                            @else
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-university text-green-600 text-2xl"></i>
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $institution->nom }}</h3>
                                <p class="text-sm text-gray-500">{{ $institution->ville }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm">
                                <i class="fas fa-map-marker-alt text-gray-400 w-5 mr-2"></i>
                                <span class="text-gray-600">{{ $institution->adresse }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-phone text-gray-400 w-5 mr-2"></i>
                                <span class="text-gray-600">{{ $institution->telephone }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-envelope text-gray-400 w-5 mr-2"></i>
                                <span class="text-gray-600">{{ $institution->email }}</span>
                            </div>
                        </div>

                        @if ($institution->accreditationActive)
                            <div class="bg-green-50 border border-green-200 rounded p-3 mb-4">
                                <div class="flex items-center text-green-700 text-sm">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <span>
                                        Accréditation valide jusqu'au
                                        {{ $institution->accreditationActive->date_fin->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-between items-center">
                            <span
                                class="px-2 py-1 text-xs rounded-full 
                        {{ $institution->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $institution->statut === 'actif' ? 'Actif' : 'Inactif' }}
                            </span>

                            <a href="{{ route('public.institutions.show', $institution->uuid) }}"
                                class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Voir détails
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <i class="fas fa-building text-gray-300 text-6xl mb-4"></i>
                    <p class="text-gray-500">Aucune institution trouvée</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $institutions->links() }}
        </div>
    </div>
@endsection
