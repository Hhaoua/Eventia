@extends('layouts.app')

@section('title', 'Accueil - Eventia')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 text-white overflow-hidden">
    {{-- Motif de fond --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.1%22%3E%3Ccircle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;2&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Colonne gauche --}}
            <div class="space-y-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center space-x-2 bg-white bg-opacity-10 backdrop-blur-sm px-4 py-2 rounded-full">
                        ⭐
                        <span class="text-sm font-medium text-black">La plateforme événementielle ultime</span>
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-bold leading-tight">
                        Créez des 
                        <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">
                            événements exceptionnels
                        </span>
                        sans effort
                    </h1>

                    <p class="text-xl lg:text-2xl text-purple-100 leading-relaxed">
                        Des rencontres intimistes aux grandes conférences, gérez chaque aspect de vos événements 
                        avec notre plateforme complète : inscriptions, billetterie et analyses – tout en un seul endroit.
                    </p>
                </div>

                {{-- Statistiques --}}
                <div class="grid grid-cols-3 gap-8 py-8 text-center">
                    <div>
                        <div class="text-2xl lg:text-3xl font-bold text-yellow-300">10K+</div>
                        <div class="text-sm lg:text-base text-purple-200">Événements créés</div>
                    </div>
                    <div>
                        <div class="text-2xl lg:text-3xl font-bold text-yellow-300">500K+</div>
                        <div class="text-sm lg:text-base text-purple-200">Participants heureux</div>
                    </div>
                    <div>
                        <div class="text-2xl lg:text-3xl font-bold text-yellow-300">95%</div>
                        <div class="text-sm lg:text-base text-purple-200">Taux de satisfaction</div>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('register.form') }}" class="group bg-white text-purple-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-opacity-95 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2">
        
                        <span>Commencer maintenant</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="group bg-transparent border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-purple-600 transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                        
                        <span>Explorer les événements</span>
                    </a>
                </div>

                {{-- Indicateurs de confiance --}}
                <div class="flex items-center space-x-6 pt-4">
                    <div class="flex items-center space-x-2">
                        <div class="flex -space-x-2">
                            <img class="h-8 w-8 rounded-full border-2 border-white" src="https://images.pexels.com/photos/1181686/pexels-photo-1181686.jpeg" alt="Utilisateur" />
                            <img class="h-8 w-8 rounded-full border-2 border-white" src="https://images.pexels.com/photos/1181671/pexels-photo-1181671.jpeg" alt="Utilisateur" />
                            <img class="h-8 w-8 rounded-full border-2 border-white" src="https://images.pexels.com/photos/1181677/pexels-photo-1181677.jpeg" alt="Utilisateur" />
                        </div>
                        <span class="text-sm text-purple-200">Plébiscité par des organisateurs du monde entier</span>
                    </div>
                </div>
            </div>

            {{-- Colonne droite - Visuel --}}
            <div class="relative">
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8 border border-white border-opacity-20 space-y-6">
                    {{-- Exemple de cartes --}}
                    @foreach ([
                        ['icon'=>'📅','title'=>'Sommet de l’innovation technologique','date'=>'15 mars 2025','participants'=>'234 inscrits','price'=>'299 €','bg'=>'bg-white'],
                        ['icon'=>'👥','title'=>'Concours de pitch startups','date'=>'28 février 2025','participants'=>'67 inscrits','price'=>'Gratuit','bg'=>'bg-white'],
                        ['icon'=>'📊','title'=>'Statistiques & Support','date'=>'','participants'=>'','price'=>'','bg'=>'bg-gradient-to-r from-green-400 to-emerald-500 text-white']
                    ] as $card)
                    <div class="{{ $card['bg'] }} rounded-xl p-4 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $card['title'] }}</h3>
                                @if($card['date']) <p class="text-sm text-gray-600">{{ $card['date'] }}</p> @endif
                                @if($card['participants']) <span class="text-green-600 font-medium">{{ $card['participants'] }}</span> @endif
                                @if($card['price']) <span class="text-purple-600">{{ $card['price'] }}</span> @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Éléments flottants --}}
                <div class="absolute -top-4 -right-4 w-20 h-20 bg-yellow-300 rounded-full opacity-20 animate-pulse"></div>
                <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-orange-300 rounded-full opacity-20 animate-pulse delay-1000"></div>
            </div>
        </div>
    </div>
</section>

{{-- Section Événements à la une --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-flex items-center space-x-2 bg-orange-100 text-orange-700 px-4 py-2 rounded-full mb-4">
                ⭐
                <span class="text-sm font-medium">Événements à la une</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Ne manquez pas ces événements incroyables
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Une sélection spéciale d’événements offrant des expériences uniques, des opportunités d’apprentissage et des moments de réseautage.
            </p>
        </div>
{{-- Section Filtrage des événements --}}
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ url('/') }}" class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 01.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Filtrer les événements</h2>
                </div>
                <a href="{{ url('/') }}" class="flex items-center space-x-1 text-gray-500 hover:text-red-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="text-sm">Tout effacer</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                <!-- Recherche -->
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher un événement</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par titre..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200">
                    </div>
                </div>

                <!-- Catégorie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
                    <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200">
                        <option value="">Toutes les catégories</option>
                        @foreach(['Conférence', 'Atelier', 'Webinaire', 'Sommet', 'Autre'] as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type d'événement</label>
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200">
                        <option value="">Tous les types</option>
                        <option value="gratuit" {{ request('type') === 'gratuit' ? 'selected' : '' }}>Gratuit</option>
                        <option value="payant" {{ request('type') === 'payant' ? 'selected' : '' }}>Payant</option>
                    </select>
                </div>

                <!-- Date de début -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                    <input type="date" name="dateFrom" value="{{ request('dateFrom') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200">
                </div>

                <!-- Date de fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                    <input type="date" name="dateTo" value="{{ request('dateTo') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors duration-200">
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    Appliquer les filtres
                </button>
            </div>
        </form>
    </div>
</section>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            {{-- Boucle sur vos événements --}}
          @foreach ($events as $event)
            <div onclick="window.location='{{ route('events.show', $event) }}'"
                class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer overflow-hidden group border border-gray-100 hover:border-purple-200">
                <div class="relative overflow-hidden">
                    <img src="{{ $event->image_url }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $event->titre }}">
                    @if($event->featured)
                    <div class="absolute top-3 left-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-3 py-1 rounded-full text-sm font-medium flex items-center space-x-1">
                        ⭐<span>En vedette</span>
                    </div>
                    @endif
                    <div class="absolute top-3 right-3 px-3 py-1 rounded-full text-sm font-medium {{ $event->tarif > 0 ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                        {{ $event->tarif > 0 ? 'FCFA'.$event->tarif : 'Gratuit' }}
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm font-medium">{{ $event->type }}</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ ($event->places_max - $event->inscrits_count) > 5 ? 'text-green-600 bg-green-50' : 'text-orange-600 bg-orange-50' }}">
                            {{ $event->places_max - $event->inscrits_count }} places restantes
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors duration-200 line-clamp-2">
                        {{ $event->titre }}
                    </h3>
                    <p class="text-gray-600 text-sm leading-relaxed line-clamp-2">{{ $event->shortDescription }}</p>
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center space-x-2">📅 {{ $event->date->format('d M Y H:i') }}</div>
                        <div class="flex items-center space-x-2">📍 {{ $event->lieu }}</div>
                        <div class="flex items-center space-x-2">👥 {{ $event->inscrits_count }}/{{ $event->places_max }} participants</div>
                    </div>
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-xs text-gray-500">Organisé par <span class="font-medium text-gray-700">{{ $event->organisateur->name ?? 'Inconnu' }}</span></p>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-1.5 rounded-full" 
                            style="width: {{ $event->places_max > 0 ? ($event->inscrits_count / $event->places_max) * 100 : 0 }}%;">
                        </div>
                    </div>
                </div>
            </div>
          @endforeach

        </div>

        <div class="text-center">
            <a href="{{ route('events.index') }}" class="inline-flex items-center space-x-2 bg-purple-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-purple-700 transition-all duration-300">
                <span>Voir tous les événements</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>



{{-- Section mise en avant --}}
<div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-2">📅 Événements variés</h3>
        <p class="text-gray-600">Conférences, ateliers, concerts, festivals… Trouvez des événements pour tous les goûts.</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-2">🎟️ Billetterie en ligne</h3>
        <p class="text-gray-600">Inscrivez-vous ou achetez vos billets en quelques clics, recevez-les directement par email.</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-semibold mb-2">📊 Suivi simplifié</h3>
        <p class="text-gray-600">Organisateurs : gérez vos événements et suivez vos statistiques en temps réel.</p>
    </div>
</div>

<script>
const searchInput = document.querySelector('input[name="search"]');
const categorySelect = document.querySelector('select[name="category"]');
const typeSelect = document.querySelector('select[name="type"]');
const dateFromInput = document.querySelector('input[name="dateFrom"]');
const dateToInput = document.querySelector('input[name="dateTo"]');
const grid = document.getElementById('events-grid'); // conteneur des cartes

function fetchCards() {
    const params = new URLSearchParams({
        search: searchInput.value,
        category: categorySelect.value,
        type: typeSelect.value,
        dateFrom: dateFromInput.value,
        dateTo: dateToInput.value
    });

    fetch(`/events/cards?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.text())
    .then(html => grid.innerHTML = html)
    .catch(err => console.error(err));
}

// Écoute tous les changements
searchInput.addEventListener('input', fetchCards);
categorySelect.addEventListener('change', fetchCards);
typeSelect.addEventListener('change', fetchCards);
dateFromInput.addEventListener('change', fetchCards);
dateToInput.addEventListener('change', fetchCards);
</script>

@endsection
