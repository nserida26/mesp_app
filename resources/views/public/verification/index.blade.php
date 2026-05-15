@extends('layouts.public')

@section('title', 'Vérification d\'un diplôme')

@section('content')
    <div class="bg-gradient-to-r from-green-600 to-green-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-3xl font-bold mb-4">
                    <i class="fas fa-check-circle mr-3"></i>
                    Vérification d'Authenticité
                </h1>
                <p class="text-xl">
                    Vérifiez en temps réel la validité d'une inscription dans l'enseignement supérieur privé
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-12">

        <!-- Messages de session -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Formulaire de vérification principal -->
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    Vérifier par numéro de baccalauréat
                </h2>

                <form action="{{ route('public.verify.check') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="numero_bac" class="block text-sm font-medium text-gray-700 mb-2">
                            Numéro de baccalauréat <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text" name="numero_bac" id="numero_bac"
                                class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md py-3"
                                placeholder="Exemple: BAC2024001234" value="{{ old('numero_bac') }}" required>
                        </div>
                        @error('numero_bac')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Le numéro figurant sur votre relevé de notes du baccalauréat
                        </p>
                    </div>

                    <!-- CAPTCHA simple (version démo) -->
                    <div>
                        <label for="captcha" class="block text-sm font-medium text-gray-700 mb-2">
                            Code de sécurité <span class="text-red-600">*</span>
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="bg-gray-200 px-6 py-3 rounded font-mono text-xl font-bold text-gray-700">
                                {{ substr(md5(date('Ymd')), 0, 6) }}
                            </div>
                            <input type="text" name="captcha" id="captcha"
                                class="focus:ring-green-500 focus:border-green-500 block w-32 sm:text-sm border-gray-300 rounded-md py-3"
                                placeholder="Code" maxlength="6" required>
                            <input type="hidden" name="captcha_expected" value="{{ substr(md5(date('Ymd')), 0, 6) }}">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Recopiez le code affiché pour continuer
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <button type="submit"
                            class="bg-green-600 text-white px-8 py-3 rounded-md hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-search mr-2"></i>
                            Vérifier l'inscription
                        </button>

                        <button type="reset"
                            class="text-gray-600 hover:text-gray-800 px-4 py-2 rounded-md border border-gray-300 hover:border-gray-400 transition">
                            <i class="fas fa-redo mr-2"></i>
                            Réinitialiser
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informations supplémentaires -->
            <div class="bg-gray-50 px-8 py-6 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Vérification Sécurisée</h3>
                        <p class="text-sm text-gray-600">
                            Les données personnelles sont protégées et ne sont jamais affichées
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Temps Réel</h3>
                        <p class="text-sm text-gray-600">
                            Résultat instantané basé sur les données du Ministère
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-qrcode text-purple-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Code QR Unique</h3>
                        <p class="text-sm text-gray-600">
                            Chaque vérification génère un code QR officiel
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vérification alternative par QR Code -->
        <div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-qrcode mr-2 text-green-600"></i>
                    Vérification par QR Code
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 mb-4">
                            Si vous disposez d'une attestation d'inscription avec un QR code,
                            vous pouvez scanner ou saisir le code ci-dessous.
                        </p>

                        <form id="qrForm" onsubmit="redirectToQR(event)">
                            <div class="flex">
                                <input type="text" id="qrCodeInput" name="code"
                                    placeholder="Code QR ou numéro de vérification"
                                    class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    required>
                                <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-r-md hover:bg-green-700 transition">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-2">Où trouver le QR code ?</h4>
                        <ul class="text-sm text-gray-600 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span>Sur votre attestation d'inscription officielle</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span>Sur votre carte d'étudiant (si équipée)</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 mr-2 mt-1"></i>
                                <span>Dans votre espace étudiant en ligne</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-question-circle mr-2 text-green-600"></i>
                    Questions fréquentes
                </h3>

                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-3">
                        <button onclick="toggleFAQ(1)"
                            class="flex justify-between items-center w-full text-left font-medium text-gray-700 hover:text-gray-900">
                            <span>Pourquoi dois-je vérifier mon inscription ?</span>
                            <i id="icon1" class="fas fa-chevron-down text-gray-400"></i>
                        </button>
                        <div id="faq1" class="hidden mt-2 text-sm text-gray-600">
                            La vérification permet de s'assurer que votre établissement est accrédité et que votre diplôme
                            sera reconnu par l'État.
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-3">
                        <button onclick="toggleFAQ(2)"
                            class="flex justify-between items-center w-full text-left font-medium text-gray-700 hover:text-gray-900">
                            <span>Mes données sont-elles protégées ?</span>
                            <i id="icon2" class="fas fa-chevron-down text-gray-400"></i>
                        </button>
                        <div id="faq2" class="hidden mt-2 text-sm text-gray-600">
                            Oui, le portail n'affiche que le statut de l'inscription. Les informations personnelles (nom,
                            numéro national) ne sont jamais exposées publiquement.
                        </div>
                    </div>

                    <div>
                        <button onclick="toggleFAQ(3)"
                            class="flex justify-between items-center w-full text-left font-medium text-gray-700 hover:text-gray-900">
                            <span>Que faire si mon inscription n'est pas trouvée ?</span>
                            <i id="icon3" class="fas fa-chevron-down text-gray-400"></i>
                        </button>
                        <div id="faq3" class="hidden mt-2 text-sm text-gray-600">
                            Contactez votre établissement pour vérifier que votre inscription a bien été déclarée au
                            Ministère. En cas de persistance, contactez le service d'assistance du Ministère.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="mt-8 text-center">
            <p class="text-gray-600">
                <i class="fas fa-headset mr-2"></i>
                Besoin d'aide ? Contactez l'assistance du Ministère
            </p>
            <p class="text-sm text-gray-500 mt-1">
                <i class="fas fa-phone mr-1"></i> +212 5XX XX XX XX &nbsp;|&nbsp;
                <i class="fas fa-envelope mr-1"></i> assistance-verification@mesrs.gov.mr
            </p>
        </div>
    </div>

    <script>
        function toggleFAQ(id) {
            const element = document.getElementById('faq' + id);
            const icon = document.getElementById('icon' + id);

            element.classList.toggle('hidden');

            if (element.classList.contains('hidden')) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            } else {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
        }
    </script>

@endsection
@push('scripts')
    <script>
        function redirectToQR(event) {
            event.preventDefault();

            const codeInput = document.getElementById('qrCodeInput');
            const code = codeInput.value.trim();

            if (!code) {
                alert('Veuillez saisir un code QR valide');
                return false;
            }

            // Redirection vers la route avec le paramètre code

        }

        // Fonction pour gérer le toggle FAQ
        function toggleFAQ(id) {
            const element = document.getElementById('faq' + id);
            const icon = document.getElementById('icon' + id);

            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                element.classList.add('hidden');
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }

        // Permettre la soumission avec la touche Entrée
        document.getElementById('qrCodeInput')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                redirectToQR(e);
            }
        });
    </script>
@endpush
