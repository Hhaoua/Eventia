@extends('layouts.app')

@section('title', 'Tableau de bord organisateur')

@section('content')
<link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">

<div class="min-h-screen bg-gradient-to-br from-purple-50 to-white">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- 1. Header -->
    <header class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10">
      <div>
        <h1 class="text-3xl font-extrabold text-purple-900 tracking-tight">Tableau de bord</h1>
        <p class="mt-1 text-purple-600">Bienvenue {{ Auth::user()->nom }}, prêt·e à faire vibrer votre communauté ?</p>
      </div>
      <a href="{{ route('events.create') }}"
         class="group inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-5 py-3 rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 transition">
        <span class="material-symbols-rounded group-hover:rotate-90 transition-transform">add_circle</span>
        <span class="font-semibold">Nouvel événement</span>
      </a>
    </header>

    <!-- 2. Tabs cliquables -->
    @php
      $tabs = [
        'overview'  => ['label'=>'Vue d’ensemble', 'icon'=>'bar_chart'],
        'events'    => ['label'=>'Mes événements', 'icon'=>'event'],
        'analytics' => ['label'=>'Analyses',       'icon'=>'trending_up'],
        'settings'  => ['label'=>'Paramètres',     'icon'=>'settings'],
      ];
      $activeTab = request()->query('tab', 'overview');
    @endphp

    <div class="mb-8">
      <nav class="flex space-x-2 bg-white/70 rounded-2xl p-2 shadow-sm border border-purple-100">
        @foreach($tabs as $id => $tab)
          <a href="?tab={{ $id }}"
             class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl font-medium text-sm transition
                    {{ $activeTab === $id ? 'bg-gradient-to-r from-purple-500 to-indigo-600 text-white shadow' : 'text-purple-700 hover:bg-purple-50' }}">
            <span class="material-symbols-rounded text-lg">{{ $tab['icon'] }}</span>
            <span>{{ $tab['label'] }}</span>
          </a>
        @endforeach
      </nav>
    </div>

    <!-- 3. Contenu -->
    <div class="space-y-8">

      @if($activeTab === 'overview')
        <!-- KPI -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          @foreach([
              ['Total événements', $stats['totalEvents'], 'calendar_month', 'text-purple-600'],
              ['Participants', $stats['totalParticipants'], 'group', 'text-indigo-500'],
              ['Revenus', $stats['totalRevenue'].' FCFA', 'paid', 'text-pink-500'],
              ['Taux participation', $stats['avgAttendance'].' %', 'insights', 'text-purple-400']
          ] as $kpi)
            <div class="p-5 rounded-2xl bg-white border border-purple-100 shadow hover:shadow-lg transition">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm text-purple-700">{{ $kpi[0] }}</p>
                  <p class="mt-1 text-2xl font-bold text-purple-900">{{ $kpi[1] }}</p>
                </div>
                <span class="material-symbols-rounded text-3xl {{ $kpi[3] }} opacity-80">{{ $kpi[2] }}</span>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Recent events -->
        <div class="bg-white rounded-2xl border border-purple-100 shadow">
          <div class="px-6 py-4 border-b border-purple-100">
            <h3 class="text-lg font-semibold text-purple-900">Événements récents</h3>
          </div>
          <div class="p-6 space-y-4">
            @forelse($recentEvents as $event)
              <div class="group flex items-center gap-4 p-4 rounded-xl hover:bg-purple-50 transition">
                <img src="{{ $event->image_url ?? 'https://source.unsplash.com/100x100/?party,'.$event->id }}"
                     alt="{{ $event->titre }}" class="w-14 h-14 object-cover rounded-xl shadow">
                <div class="flex-1">
                  <h4 class="font-semibold text-purple-900">{{ $event->titre }}</h4>
                  <p class="text-sm text-purple-600">{{ $event->date->format('d/m/Y') }} • {{ $event->lieu }}</p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-medium text-purple-900">{{ $event->participants()->count() }} inscrits</p>
                  <p class="text-xs text-purple-500">sur {{ $event->places_max }} places</p>
                </div>
                <a href="{{ route('events.show', $event) }}" class="material-symbols-rounded text-purple-400 group-hover:text-purple-600 transition">arrow_forward</a>
              </div>
            @empty
              <p class="text-purple-600 text-center py-6">Aucun événement pour le moment.</p>
            @endforelse
          </div>
        </div>
      @endif

      @if($activeTab === 'events')
        <div class="bg-white rounded-2xl border border-purple-100 shadow divide-y divide-purple-100">
          <div class="px-6 py-4">
            <h3 class="text-lg font-semibold text-purple-900">Gestion des événements</h3>
          </div>
          @forelse($events as $event)
            <div class="flex items-center justify-between px-6 py-4 hover:bg-purple-50 transition">
              <div>
                <h4 class="font-medium text-purple-900">{{ $event->titre }}</h4>
                <p class="text-sm text-purple-600">{{ $event->date->format('d/m/Y H:i') }}</p>
              </div>
              <div class="flex items-center gap-2">
                <a href="{{ route('events.edit', $event) }}" class="px-3 py-1.5 text-sm rounded-lg text-purple-600 hover:bg-purple-100 transition">Modifier</a>
                <a href="{{ route('events.show', $event) }}" class="px-3 py-1.5 text-sm rounded-lg text-indigo-600 hover:bg-indigo-100 transition">Voir</a>
  </div>
            </div>
          @empty
            <p class="text-purple-600 text-center py-8">Aucun événement disponible.</p>
          @endforelse
        </div>
      @endif

      @if($activeTab === 'analytics' || $activeTab === 'settings')
        <div class="bg-white rounded-2xl border border-purple-100 shadow p-8 text-center">
          <span class="material-symbols-rounded text-5xl text-purple-300">construction</span>
          <p class="mt-3 text-purple-600">Fonctionnalité en cours de développement… Revenez vite !</p>
        </div>
      @endif

    </div><!-- /content -->
  </div>
</div>
@endsection