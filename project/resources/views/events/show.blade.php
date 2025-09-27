@extends('layouts.app')
@section('title', $event->titre)

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <!-- Image -->
    <img src="{{ $event->image_url ?? 'https://via.placeholder.com/800x400' }}" 
         alt="{{ $event->titre }}" 
         class="w-full h-64 object-cover rounded-xl mb-6">

    <!-- Titre & description courte -->
    <h1 class="text-3xl font-bold mb-2">{{ $event->titre }}</h1>
    <p class="text-gray-600 mb-4">{{ $event->shortDescription }}</p>

    <!-- Infos principales -->
    <div class="grid grid-cols-2 gap-4 mb-6 text-gray-700">
        <div>📅 {{ $event->date->format('d/m/Y H:i') }}</div>
        <div>📍 {{ $event->lieu }}</div>
        <div>👥 {{ $event->inscrits_count }}/{{ $event->places_max }} participants</div>
        <div>
            💰 
            @if($event->type === 'paid')
                {{ number_format($event->tarif, 2, ',', ' ') }} €
            @else
                Gratuit
            @endif
        </div>
    </div>

    <!-- Description complète -->
    <div class="prose max-w-none text-gray-800 mb-8">
        {!! nl2br(e($event->description)) !!}
    </div>

    <!-- Actions -->
    <div>
        @auth
            @php
                $alreadyRegistered = $event->participants->contains(auth()->user()->id);
                $isFull = $event->inscrits_count >= $event->places_max;
            @endphp

            @if($alreadyRegistered)
                <button class="px-6 py-2 bg-gray-400 text-white font-semibold rounded-lg shadow cursor-not-allowed">
                    Déjà inscrit
                </button>
            @elseif($isFull)
                <button class="px-6 py-2 bg-red-500 text-white font-semibold rounded-lg shadow cursor-not-allowed">
                    Événement complet
                </button>
            @else
                <form action="{{ route('events.register', $event->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700">
                        S’inscrire
                    </button>
                </form>
            @endif
        @else
            <a href="{{ route('login.form') }}" 
               class="px-6 py-2 bg-gray-600 text-white font-semibold rounded-lg shadow hover:bg-gray-700">
               Connectez-vous pour vous inscrire
            </a>
        @endauth
    </div>
</div>
@endsection
