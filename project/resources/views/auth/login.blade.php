@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
            <!-- En-tête -->
            <div class="flex items-center justify-between p-6 border-b border-gray-100 relative">
                <h2 class="text-xl font-semibold text-gray-900 text-center w-full">Se connecter</h2>
                <a href="{{ route('home') }}" class="absolute right-4 top-4 p-2 text-gray-400 hover:text-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <!-- Formulaire -->
            <form method="POST" action="{{ route('login') }}" class="p-6 space-y-4">
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

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           placeholder="ex: marie@eventia.fr">
                </div>

                <!-- Mot de passe -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <div class="relative">
                        <input type="password" name="password" id="login_password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 pr-10"
                               placeholder="••••••••" required>
                        <button type="button" onclick="togglePassword('login_password', this)"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-purple-600 transition">
                            <!-- Icône œil ouverte -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M1.458 12C2.732 7.943 6.522 5 12 5c5.478 0 9.268 2.943 10.542 7-1.274 4.057-5.064 7-10.542 7-5.478 0-9.268-2.943-10.542-7z" />
                                <circle cx="12" cy="12" r="3" stroke-width="2" stroke="currentColor" fill="none" />
                            </svg>

                            <!-- Icône œil barrée (cachée par défaut) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 3l18 18M10.477 10.477A3 3 0 0112 9c5.478 0 9.268 2.943 10.542 7a11.55 11.55 0 01-3.75 4.362M9.88 9.88A3 3 0 0012 15a3 3 0 003-3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Bouton -->
                <button type="submit"
                        class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition">
                    Se connecter
                </button>

                <div class="text-center pt-4">
                    <a href="{{ route('register.form') }}" class="text-purple-600 hover:text-purple-700 text-sm">
                        Pas encore de compte ? Inscrivez-vous
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script -->
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
