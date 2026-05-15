@extends('layouts.public')

@section('title', $institution->nom)

@section('content')
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-white">
                <a href="{{ route('public.institutions') }}" class="text-blue-100 hover:text-white mb-4 inline-block">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
                <h1 class="text-3xl font-bold">{{ $institution->nom }}</h1>
                <p class="text-blue-100 mt-2">{{ $institution->ville }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Informations principales -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    @if ($institution->logo_path)
                        <img src="{{ Storage::url($institution->logo_path) }}" alt="{{ $institution->nom }}"
                            class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    @else
                        <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-university text-blue-600 text-4xl"></i>
                        </div>
                    @endif

                    <h2 class="text-xl font-bold text-center text-gray-900 mb-4">{{ $institution->nom }}</h2>

                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-500 uppercase">Code établissement</label>
                            <p class="font-mono text-gray-900">{{ $institution->code_etablissement }}</p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase">Adresse</label>
                            <p class="text-gray-900">{{ $institution->adresse }}</p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase">Contact</label>
                            <p class="text-gray-900">{{ $institution->telephone }}</p>
                            <p class="text-gray-900">{{ $institution->email }}</p>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 uppercase">Statut</label>
                            <p>
                                <span
                                    class="px-2 py-1 text-xs rounded-full 
                                {{ $institution->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $institution->statut === 'actif' ? 'Actif' : 'Inactif' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Accréditation -->
                @if ($institution->accreditationActive)
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-certificate text-green-600 mr-2"></i>
                            Accréditation en cours
                        </h3>

                        <div class="space-y-2 text-sm">
                            <div>
                                <label class="text-xs text-gray-500">Numéro d'arrêté</label>
                                <p class="font-mono">{{ $institution->accreditationActive->numero_arrete }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Date de début</label>
                                <p>{{ $institution->accreditationActive->date_debut->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Date d'expiration</label>
                                <p>{{ $institution->accreditationActive->date_fin->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500">Jours restants</label>
                                <p
                                    class="font-semibold {{ $institution->accreditationActive->date_fin->diffInDays(now()) < 90 ? 'text-orange-600' : 'text-green-600' }}">
                                    {{ $institution->accreditationActive->date_fin->diffInDays(now()) }} jours
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Filieres -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                        Filières Autorisées
                    </h2>

                    @if ($institution->filieres->count() > 0)
                        <div class="space-y-4">
                            @foreach ($institution->filieres as $filiere)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-lg">{{ $filiere->nom }}</h3>
                                            <p class="text-sm text-gray-500">Code: {{ $filiere->code_filiere }}</p>
                                        </div>
                                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($filiere->niveau) }}
                                        </span>
                                    </div>

                                    <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <label class="text-xs text-gray-500">Durée</label>
                                            <p class="font-medium">{{ $filiere->duree_semestres }} semestres</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Capacité</label>
                                            <p class="font-medium">{{ $filiere->capacite_accueil }} étudiants</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Arrêté d'autorisation</label>
                                            <p class="font-mono text-xs">{{ $filiere->numero_arrete_autorisation }}</p>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500">Date d'autorisation</label>
                                            <p>{{ $filiere->date_arrete_autorisation->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">
                            Aucune filière déclarée pour cet établissement
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
