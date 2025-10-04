@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 text-center w-full">Créer un compte</h2>
                <a href="{{ route('home') }}" class="absolute right-4 top-4 p-2 text-gray-400 hover:text-gray-600">
                    ✕
                </a>
            </div>

            <form method="POST" action="{{ route('register') }}" class="p-6 space-y-4">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Champ nom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Champ prénom -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Champ email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="votre@email.com">
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 pr-10"
                               required>

                        <button type="button" onclick="togglePassword('password', this)"
                                class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-purple-600">

                            <!-- Icône œil ouvert -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <!-- Icône œil barré (masqué par défaut) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden eye-closed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.132-3.368M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirmation mot de passe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer mot de passe</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 pr-10"
                               required>

                        <button type="button" onclick="togglePassword('password_confirmation', this)"
                                class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-purple-600">

                            <!-- Icône œil ouvert -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <!-- Icône œil barré -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden eye-closed" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.132-3.368M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Bouton de validation -->
                <button type="submit"
                        class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition">
                    Créer le compte
                </button>

                <!-- Lien vers la connexion -->
                <div class="text-center pt-4">
                    <a href="{{ route('login.form') }}" class="text-purple-600 hover:text-purple-700 text-sm">
                        Déjà un compte ? Connectez-vous
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script pour afficher/masquer le mot de passe -->
    <script>
        function togglePassword(fieldId, button) {
            const input = document.getElementById(fieldId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (input.type === "password") {
                input.type = "text";
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = "password";
                eyeClosed.classList.add('hidden');
                eyeOpen.classList.remove('hidden');
            }
        }
    </script>
@endsection
