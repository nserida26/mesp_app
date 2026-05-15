@extends('layouts.public')

@section('title', 'Résultat de vérification')

@section('content')
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-clipboard-check mr-3"></i>
                    Résultat de la vérification
                </h1>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">

        <div class="bg-white rounded-lg shadow-xl overflow-hidden">

            @if (isset($resultat) && $resultat['status'] === 'valide')
                <!-- Résultat Valide -->
                <div class="bg-green-600 text-white px-6 py-8 text-center">
                    <i class="fas fa-check-circle text-5xl mb-3"></i>
                    <h2 class="text-2xl font-bold">✅ Inscription Valide</h2>
                    <p class="mt-2 text-green-100">
                        L'inscription est conforme et l'établissement est accrédité par le Ministère
                    </p>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informations Académiques -->
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg mb-4 flex items-center">
                                <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                                Informations Académiques
                            </h3>
                            <dl class="space-y-3">
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-xs text-gray-500 uppercase">Niveau d'études</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ ucfirst($resultat['niveau']) }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-xs text-gray-500 uppercase">Filière</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $resultat['filiere'] }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-xs text-gray-500 uppercase">Année universitaire</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $resultat['annee'] }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-xs text-gray-500 uppercase">Semestre en cours</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        Semestre {{ $resultat['semestre'] }}
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Informations Institution -->
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg mb-4 flex items-center">
                                <i class="fas fa-university text-blue-600 mr-2"></i>
                                Établissement
                            </h3>
                            <dl class="space-y-3">
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-xs text-gray-500 uppercase">Institution</dt>
                                    <dd class="text-lg font-medium text-gray-900">
                                        {{ $resultat['institution'] }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-xs text-gray-500 uppercase">Statut d'accréditation</dt>
                                    <dd class="text-green-600 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Accréditée et en règle
                                    </dd>
                                </div>
                                <div class="bg-gray-50 p-3 rounded">
                                    <dt class="text-xs text-gray-500 uppercase">Numéro d'inscription</dt>
                                    <dd class="text-md font-mono text-gray-700">
                                        {{ $resultat['numero_inscription'] ?? 'Non disponible' }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- QR Code de vérification -->
                    @if (isset($resultat['qr_code_verification']))
                        <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-qrcode text-blue-600 mr-2"></i>
                                Code de Vérification Officiel
                            </h4>

                            <div class="flex flex-col md:flex-row items-center gap-6">
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <!-- Simulation QR Code -->
                                    <div class="w-48 h-48 bg-gray-200 flex items-center justify-center">
                                        <div class="text-center">
                                            <i class="fas fa-qrcode text-6xl text-gray-400 mb-2"></i>
                                            <p class="text-xs text-gray-500 break-all">
                                                {{ $resultat['qr_code_verification'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <p class="text-sm text-gray-600 mb-3">
                                        Ce QR code atteste de la validité de l'inscription.
                                        Il peut être scanné pour une vérification instantanée.
                                    </p>
                                    <div class="bg-white p-3 rounded border border-gray-200">
                                        <p class="text-xs text-gray-500 mb-1">Code unique :</p>
                                        <p class="font-mono text-sm text-gray-700 break-all">
                                            {{ $resultat['qr_code_verification'] }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Certification -->
                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-certificate text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    <strong>Vérification certifiée</strong><br>
                                    Cette vérification a été effectuée le {{ date('d/m/Y à H:i') }} et est conforme
                                    aux données officielles du Ministère de l'Enseignement Supérieur.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Résultat Invalide -->
                <div class="bg-red-600 text-white px-6 py-8 text-center">
                    <i class="fas fa-exclamation-triangle text-5xl mb-3"></i>
                    <h2 class="text-2xl font-bold">❌ Vérification Impossible</h2>
                    <p class="mt-2 text-red-100">
                        Aucune inscription valide trouvée avec ces informations
                    </p>
                </div>

                <div class="p-8">
                    <div class="text-center">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                            <i class="fas fa-info-circle text-red-500 text-2xl mb-3"></i>
                            <p class="text-gray-700 mb-4">
                                Le numéro de baccalauréat fourni ne correspond à aucun étudiant actuellement
                                inscrit dans une institution accréditée par le Ministère.
                            </p>

                            <div class="text-left bg-white p-4 rounded border border-gray-200">
                                <h4 class="font-semibold text-gray-900 mb-2">Causes possibles :</h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-start">
                                        <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                        <span>Le numéro de baccalauréat saisi est incorrect</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                        <span>L'étudiant n'est pas inscrit pour l'année universitaire en cours</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                        <span>L'établissement n'a pas encore déclaré l'inscription</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-times-circle text-red-500 mr-2 mt-1"></i>
                                        <span>L'établissement n'est pas accrédité ou son accréditation a expiré</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('public.verify') }}"
                                class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition">
                                <i class="fas fa-redo mr-2"></i>
                                Nouvelle vérification
                            </a>

                            <a href="{{ route('public.institutions') }}"
                                class="inline-block bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 transition">
                                <i class="fas fa-building mr-2"></i>
                                Voir les institutions accréditées
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <div class="flex gap-3">
                        <a href="{{ route('public.verify') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left mr-1"></i>
                            Nouvelle recherche
                        </a>

                        <span class="text-gray-300">|</span>

                        <a href="{{ route('public.home') }}" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-home mr-1"></i>
                            Accueil
                        </a>
                    </div>

                    <div class="flex gap-3">
                        <button onclick="window.print()" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-print mr-1"></i>
                            Imprimer
                        </button>

                        <span class="text-gray-300">|</span>

                        <button onclick="downloadPDF()" class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-download mr-1"></i>
                            Télécharger PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information légale -->
        <div class="mt-6 text-center text-xs text-gray-500">
            <p>
                <i class="fas fa-gavel mr-1"></i>
                Ce document a valeur d'attestation officielle conformément à l'arrêté ministériel n°XXXX-XX
            </p>
            <p class="mt-1">
                Date de vérification : {{ date('d/m/Y H:i:s') }} |
                Référence : VERIF-{{ date('Ymd') }}-{{ rand(1000, 9999) }}
            </p>
        </div>
    </div>

    <script>
        function downloadPDF() {
            alert(
                'Fonctionnalité de téléchargement PDF en cours de développement.\n\nCette fonction permettra de télécharger une attestation officielle au format PDF.'
            );
            // Implémentation future : window.location.href = '{{ route('public.verify.download') }}';
        }
    </script>

@endsection
