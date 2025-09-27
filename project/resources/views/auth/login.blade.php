@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4">
     <div class="flex items-center justify-between p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 text-center w-full">Se connecter</h2>
            <a href="{{ route('home') }}" class="absolute right-4 top-4 p-2 text-gray-400 hover:text-gray-600">
                ✕
            </a>
        </div>

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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="ex: marie@eventia.fr">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                       placeholder="••••••••">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                Se connecter
            </button>

            <div class="text-center pt-4">
                <a href="{{ route('register.form') }}" class="text-blue-600 hover:text-blue-700 text-sm">
                    Pas encore de compte ? Inscrivez-vous
                </a>
            </div>

            <!-- Astuce : comptes démo -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mt-4">
                <p class="text-sm text-blue-800 font-medium mb-1">Comptes de démonstration :</p>
                <p class="text-xs text-blue-700">Organisateur : marie@eventia.fr</p>
                <p class="text-xs text-blue-700">Participant : jean@eventia.fr</p>
            </div>
        </form>
    </div>
</div>
@endsection
