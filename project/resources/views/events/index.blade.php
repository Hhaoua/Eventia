@extends('layouts.app')

@section('title', 'Tous les événements')

@section('content')
<h1 class="text-3xl font-bold mb-6">Tous les événements</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach ($events as $event)
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-semibold text-lg">{{ $event->title }}</h2>
            <p class="text-gray-600">{{ $event->date }}</p>
            <p class="text-gray-700 mt-2">{{ $event->description }}</p>
        </div>
    @endforeach
</div>
@endsection
