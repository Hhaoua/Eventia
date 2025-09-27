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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                       placeholder="votre@email.com">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer mot de passe</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            </div>

            <!-- Choix du rôle -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type de compte</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer 
                        {{ old('role', 'participant') === 'participant' ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-gray-300' }}">
                        <input type="radio" name="role" value="participant"
                               class="hidden" {{ old('role', 'participant') === 'participant' ? 'checked' : '' }}>
                        <span class="text-sm">👤 Participant</span>
                    </label>

                    <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer
                        {{ old('role') === 'organisateur' ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-gray-300' }}">
                        <input type="radio" name="role" value="organisateur"
                               class="hidden" {{ old('role') === 'organisateur' ? 'checked' : '' }}>
                        <span class="text-sm">🏢 Organisateur</span>
                    </label>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition">
                Créer le compte
            </button>

            <div class="text-center pt-4">
                <a href="{{ route('login.form') }}" class="text-purple-600 hover:text-purple-700 text-sm">
                    Déjà un compte ? Connectez-vous
                </a>
            </div>
        </form>
    </div>
</div>
@endsection