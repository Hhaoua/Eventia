<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eventia')</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">

<!-- Header -->
<header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-purple-900 group-hover:text-purple-700 transition">Eventia</h1>
                    <p class="text-xs text-gray-600">Gestion d'événements</p>
                </div>
            </a>

            {{-- Barre de recherche --}}
            <div class="flex-1 max-w-2xl mx-8">
                <form action="{{ route('events.search', [], false) }}" method="GET" class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                    </svg>
                    <input
                        type="text"
                        name="q"
                        placeholder="Rechercher des événements..."
                        value="{{ request('q') }}"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                    />
                </form>
            </div>

            {{-- Actions utilisateur --}}
            <div class="flex items-center space-x-4">
                @auth
                    {{-- Dropdown utilisateur --}}
                    <div class="relative">
                        <button id="userMenuButton" class="flex items-center space-x-2 focus:outline-none">
                            <div class="bg-purple-100 p-2 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4.992 4.992 0 0112 15a4.992 4.992 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ auth()->user()->nom }}</span>
                        </button>

                        <!-- Dropdown -->
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            @php
                                $isParticipant = auth()->user()->role === 'participant';
                            @endphp

                            <a href="{{ auth()->user()->role === 'participant' ? route('profile') : route('dashboard') }}"
                               class="flex items-center gap-2 px-4 py-2 text-purple-700 hover:bg-purple-50 transition">
                                <span class="material-symbols-rounded text-sm">
                                    {{ auth()->user()->role === 'participant' ? 'person' : 'dashboard' }}
                                </span>
                                <span>
                                    {{ auth()->user()->role === 'participant' ? 'Mon profil' : 'Tableau de bord' }}
                                </span>
                            </a>
                            {{-- Paramètres uniquement admin --}}
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('dashboard') }}?tab=settings"
                                   class="flex items-center gap-2 px-4 py-2 text-red-700 hover:bg-red-50 transition">
                                    <span class="material-symbols-rounded text-sm">admin_panel_settings</span>
                                    <span>Paramètres</span>
                                </a>
                            @endif

                            {{-- Déconnexion --}}
                            <form action="{{ route('logout') }}" method="POST" class="border-t border-gray-100">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Se déconnecter</button>
                            </form>
                        </div>
                    </div>

                    <script>
                        const btn = document.getElementById('userMenuButton');
                        const dropdown = document.getElementById('userDropdown');

                        btn.addEventListener('click', () => {
                            dropdown.classList.toggle('hidden');
                        });

                        document.addEventListener('click', (e) => {
                            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                                dropdown.classList.add('hidden');
                            }
                        });
                    </script>

                @else
                    <a href="{{ route('login.form') }}" class="flex items-center space-x-2 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                        <span>Se connecter</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

<!-- Contenu principal -->
<main class="container mx-auto py-6">
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-gray-900 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Marque --}}
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-2xl font-bold">Eventia</span>
                </div>
                <p class="text-gray-300 leading-relaxed">
                    La plateforme ultime pour créer, gérer et promouvoir des événements de toutes tailles.
                    Connectez-vous avec votre public et rendez chaque événement inoubliable.
                </p>
            </div>

            {{-- Liens rapides --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Liens rapides</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('events.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Parcourir les événements</a></li>
                    @auth
                        @if(auth()->user()->role === 'organisateur')
                            <li><a href="{{ route('events.create') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Créer un événement</a></li>
                        @endif
                    @endauth
                    <li><a href="#pricing" class="text-gray-300 hover:text-white transition-colors duration-200">Tarifs</a></li>
                    <li><a href="#help" class="text-gray-300 hover:text-white transition-colors duration-200">Centre d'aide</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Support</h3>
                <ul class="space-y-2">
                    <li><a href="#about" class="text-gray-300 hover:text-white transition-colors duration-200">À propos</a></li>
                    <li><a href="#contact" class="text-gray-300 hover:text-white transition-colors duration-200">Contact</a></li>
                    <li><a href="#privacy" class="text-gray-300 hover:text-white transition-colors duration-200">Politique de confidentialité</a></li>
                    <li><a href="#terms" class="text-gray-300 hover:text-white transition-colors duration-200">Conditions d'utilisation</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold">Nous contacter</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 4H8m0-8h8M3 7v14a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 01-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <span class="text-gray-300">support@eventia.com</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l3 7-1 2 5 5 2-1 7 3v2a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" />
                        </svg>
                        <span class="text-gray-300">+1 (555) 123-4567</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a2 2 0 00-2-2h-3v4zM2 9V7a2 2 0 012-2h3v4H4a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-gray-300">123 Rue des Événements, Ville, Pays</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-12 pt-8 text-center">
            <p class="text-gray-400">
                © {{ date('Y') }} Eventia. Tous droits réservés. Fait avec ❤️ pour les organisateurs d'événements.
            </p>
        </div>
    </div>
</footer>

@vite('resources/js/app.js')
</body>
</html>
