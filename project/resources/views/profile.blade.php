@extends('layouts.app')

@section('title', 'Mon profil')

@section('content')
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- En-tête -->
            <div class="bg-white rounded-2xl shadow border border-purple-100 p-6 mb-8">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 p-4 rounded-full">
                        <span class="material-symbols-rounded text-4xl text-purple-600">person</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-purple-900">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</h1>
                        <p class="text-purple-600">{{ Auth::user()->email }}</p>
                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">Participant</span>
                    </div>
                </div>
            </div>

            <!-- Mes événements à venir -->
            <div class="bg-white rounded-2xl shadow border border-purple-100 p-6">
                <h2 class="text-lg font-semibold text-purple-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-rounded text-purple-600">event_available</span>
                    Mes événements à venir
                </h2>

                @php
                    $inscriptions = Auth::user()->inscriptions()
                        ->with(['evenement', 'billet'])
                        ->whereHas('evenement', fn($q) => $q->where('date', '>=', now()))
                        ->get();
                @endphp

                @if($inscriptions->isEmpty())
                    <p class="text-purple-600 text-center py-6">Vous n’êtes inscrit à aucun événement à venir.</p>
                @else
                    <div class="space-y-4">
                        @foreach($inscriptions as $inscription)
                            @php $event = $inscription->evenement; @endphp
                            <div class="flex items-center justify-between gap-4 p-4 rounded-xl hover:bg-purple-50 transition">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $event->image_url ?? 'https://source.unsplash.com/100x100/?party,'.$event->id }}" alt="{{ $event->titre }}" class="w-16 h-16 object-cover rounded-xl shadow">
                                    <div>
                                        <h3 class="font-semibold text-purple-900">{{ $event->titre }}</h3>
                                        <p class="text-sm text-purple-600">{{ $event->date->format('d/m/Y H:i') }} • {{ $event->lieu }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('events.show', $event) }}" class="px-3 py-1.5 text-sm rounded-lg text-indigo-600 hover:bg-indigo-100 transition">Voir</a>

                                    @if($inscription->billet && $inscription->billet->file_path)
                                        <a href="{{ route('billet.download', $inscription->billet) }}"
                                           target="_blank"
                                           class="px-3 py-1.5 text-sm rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition flex items-center gap-1">
                                            <span class="material-symbols-rounded text-sm">download</span>
                                            Télécharger mon billet
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
