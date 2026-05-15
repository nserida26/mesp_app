<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Portail officiel de vérification des diplômes et institutions d'enseignement supérieur privé">
    <title>@yield('title', 'Portail Ministériel') - Enseignement Supérieur</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>

<body class="bg-gray-50">

    <!-- Navigation Publique -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-university text-green-600 text-2xl mr-2"></i>
                        <span class="font-bold text-xl text-gray-800"></span>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('public.home') }}"
                            class="{{ request()->routeIs('public.home') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Accueil
                        </a>
                        <a href="{{ route('public.verify') }}"
                            class="{{ request()->routeIs('public.verify*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Vérifier un diplôme
                        </a>
                        <a href="{{ route('public.institutions') }}"
                            class="{{ request()->routeIs('public.institutions*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Institutions
                        </a>
                        <a href="{{ route('public.filieres') }}"
                            class="{{ request()->routeIs('public.filieres*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Filières
                        </a>
                        <a href="{{ route('public.etudiants') }}"
                            class="{{ request()->routeIs('public.etudiants*') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Etudiants
                        </a>
                    </div>
                </div>

                <div class="flex items-center">
                    <a href="{{ route('login') }}"
                        class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-in-alt mr-1"></i>Espace ADMIN
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                <p>&copy; {{ date('Y') }} Ministère de l'Enseignement Supérieur. Tous droits réservés.</p>
                <p class="mt-2">
                    <i class="fas fa-shield-alt text-green-600 mr-1"></i>
                    Portail officiel de vérification - Les données personnelles sont protégées
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
