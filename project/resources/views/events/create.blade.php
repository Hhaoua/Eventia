@extends('layouts.app')

@section('title', 'Créer un nouvel événement')

@section('content')
<div class="max-w-4xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-4">Créer un nouvel événement</h1>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg space-y-6">
        @csrf

        {{-- Titre --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Titre *</label>
            <input type="text" name="titre" value="{{ old('titre') }}" required
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('titre') border-red-300 @enderror"
                placeholder="Titre de l'événement">
            @error('titre') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Courte description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Courte description *</label>
            <input type="text" name="shortDescription" value="{{ old('shortDescription') }}" required maxlength="150"
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shortDescription') border-red-300 @enderror"
                placeholder="Brève description pour les cartes">
            @error('shortDescription') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Description détaillée --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description détaillée *</label>
            <textarea name="description" rows="4" required
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-300 @enderror"
                placeholder="Description complète de votre événement...">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Image --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Image de l'événement</label>
            <input type="file" name="image" accept="image/*"
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-300 @enderror">
            @error('image') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Date et heure --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                <input type="date" name="date" value="{{ old('date') }}" required
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('date') border-red-300 @enderror">
                @error('date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Heure de début *</label>
                <input type="time" name="time" value="{{ old('time') }}" required
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('time') border-red-300 @enderror">
                @error('time') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Lieu --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu *</label>
            <input type="text" name="lieu" value="{{ old('lieu') }}" required
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('lieu') border-red-300 @enderror"
                placeholder="Lieu de l'événement">
            @error('lieu') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Catégorie --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
            <select name="category" required
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-300 @enderror">
                <option value="">Sélectionnez une catégorie</option>
                @foreach(['Conférence', 'Atelier', 'Concert', 'Sommet', 'Autre'] as $cat)
                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Type Gratuit / Payant --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type d’événement *</label>
            <div class="flex space-x-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="type" value="gratuit" {{ old('type', 'gratuit') === 'gratuit' ? 'checked' : '' }} required>
                    <span>Gratuit</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="radio" name="type" value="payant" {{ old('type') === 'payant' ? 'checked' : '' }} required>
                    <span>Payant</span>
                </label>
            </div>
            @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Prix si payant --}}
        <div id="priceField" class="{{ old('type') === 'payant' ? '' : 'hidden' }}">
            <label class="block text-sm font-medium text-gray-700 mb-1">Prix du billet (EUR) *</label>
            <input type="number" step="0.01" min="0" name="tarif" value="{{ old('tarif') }}"
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tarif') border-red-300 @enderror"
                placeholder="0.00">
            @error('tarif') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Capacité maximale --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre maximum de participants *</label>
            <input type="number" name="places_max" min="1" value="{{ old('places_max') }}" required
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('places_max') border-red-300 @enderror"
                placeholder="Ex: 100">
            @error('places_max') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Bouton --}}
        <div class="flex justify-end">
            <button type="submit"
                class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">Créer l'événement</button>
        </div>
    </form>
</div>

{{-- Script pour basculer l'affichage du prix --}}
<script>
    const radios = document.querySelectorAll('input[name="type"]');
    const priceField = document.getElementById('priceField');

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            if(radio.value === 'payant' && radio.checked){
                priceField.classList.remove('hidden');
            } else if(radio.value === 'gratuit' && radio.checked){
                priceField.classList.add('hidden');
            }
        });
    });
</script>
@endsection
