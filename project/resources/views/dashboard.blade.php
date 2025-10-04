@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
    <link href="https://fonts.googleapis.com/icon?family=Material+Symbols+Rounded" rel="stylesheet">

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

            <!-- Header -->
            <header class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-10 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-purple-900 tracking-tight">Tableau de bord</h1>
                    <p class="mt-1 text-purple-600">Bienvenue {{ Auth::user()->nom }}, prêt·e à faire vibrer votre communauté ?</p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    {{-- Boutons d'export (admin uniquement) --}}
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('analytics.pdf') }}" target="_blank"
                           class="px-3 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition flex items-center gap-2 text-sm">
                            <span class="material-symbols-rounded text-sm">download</span>
                            PDF
                        </a>
                        <a href="{{ route('analytics.excel') }}"
                           class="px-3 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition flex items-center gap-2 text-sm">
                            <span class="material-symbols-rounded text-sm">download</span>
                            Excel
                        </a>
                    @endif

                    {{-- Nouvel événement --}}
                    <a href="{{ route('events.create') }}" class="group inline-flex items-center gap-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white px-5 py-3 rounded-2xl shadow-lg hover:shadow-xl hover:scale-105 transition">
                        <span class="material-symbols-rounded group-hover:rotate-90 transition-transform">add_circle</span>
                        <span class="font-semibold">Nouvel événement</span>
                    </a>
                </div>
            </header>

            <!-- Tabs -->
              @php
                $tabs = [
                    'overview'  => ['label' => 'Vue d’ensemble', 'icon' => 'bar_chart'],
                    'events'    => ['label' => 'Mes événements', 'icon' => 'event'],
                ];

                if (auth()->user()->role === 'admin') {
                    $tabs['allevents'] = ['label' => 'Tous les événements', 'icon' => 'public'];
                    $tabs['analytics'] = ['label' => 'Analyses', 'icon' => 'trending_up'];
                    $tabs['settings']  = ['label' => 'Paramètres', 'icon' => 'settings'];
                }

                $activeTab = request()->query('tab', 'overview');
             @endphp

            <div class="mb-8">
                <nav class="flex space-x-2 bg-white/70 rounded-2xl p-2 shadow-sm border border-purple-100">
                    @foreach($tabs as $id => $tab)
                        @if($id !== 'allevents' || auth()->user()->role === 'admin')
                            <a href="?tab={{ $id }}" class="flex-1 flex items-center justify-center gap-2 px-3 py-2.5 rounded-xl font-medium text-sm transition {{ $activeTab === $id ? 'bg-gradient-to-r from-purple-500 to-indigo-600 text-white shadow' : 'text-purple-700 hover:bg-purple-50' }}">
                                <span class="material-symbols-rounded text-lg">{{ $tab['icon'] }}</span>
                                <span>{{ $tab['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </nav>
            </div>

            <!-- Content -->
            <div class="space-y-8">

                @if($activeTab === 'overview')
                    <!-- KPI -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach([
                            ['Total événements', $stats['totalEvents'], 'calendar_month', 'text-purple-600'],
                            ['Participants', $stats['totalParticipants'], 'group', 'text-indigo-500'],
                            ['Revenus', $stats['totalRevenue'].' FCFA', 'paid', 'text-pink-500'],
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
                                    <img src="{{ $event->image_url ?? 'https://source.unsplash.com/100x100/?party,'.$event->id }}" alt="{{ $event->titre }}" class="w-14 h-14 object-cover rounded-xl shadow">
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
                                    {{-- Modifier --}}
                                    <a href="{{ route('events.edit', $event) }}" class="px-3 py-1.5 text-sm rounded-lg bg-purple-100 text-purple-700 hover:bg-purple-200 transition flex items-center gap-1">
                                        <span class="material-symbols-rounded text-sm">edit</span>
                                        Modifier
                                    </a>

                                    {{-- Voir --}}
                                    <a href="{{ route('events.show', $event) }}" class="px-3 py-1.5 text-sm rounded-lg bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition flex items-center gap-1">
                                        <span class="material-symbols-rounded text-sm">visibility</span>
                                        Voir
                                    </a>

                                    @if(auth()->id() === $event->organisateur_id)
                                        {{-- Export PDF --}}
                                        <a href="{{ route('events.export.pdf', $event) }}" target="_blank"
                                           class="px-3 py-1.5 text-sm rounded-lg bg-orange-100 text-orange-700 hover:bg-orange-200 transition flex items-center gap-1">
                                            <span class="material-symbols-rounded text-sm">picture_as_pdf</span>
                                            PDF
                                        </a>

                                        {{-- Export Excel --}}
                                        <a href="{{ route('events.export.excel', $event) }}"
                                           class="px-3 py-1.5 text-sm rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200 transition flex items-center gap-1">
                                            <span class="material-symbols-rounded text-sm">download</span>
                                            Excel
                                        </a>

                                        {{-- Supprimer --}}
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet événement ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-sm rounded-lg bg-red-100 text-red-700 hover:bg-red-200 transition flex items-center gap-1">
                                                <span class="material-symbols-rounded text-sm">delete</span>
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-purple-600 text-center py-8">Aucun événement disponible.</p>
                        @endforelse
                    </div>
                @endif

                @if($activeTab === 'allevents' && auth()->user()->role === 'admin')
                    <div class="bg-white rounded-2xl border border-purple-100 shadow divide-y divide-purple-100">
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-semibold text-purple-900">Tous les événements (Admin)</h3>
                        </div>
                        @forelse($allEvents as $event)
                            <div class="flex items-center justify-between px-6 py-4 hover:bg-purple-50 transition">
                                <div>
                                    <h4 class="font-medium text-purple-900">{{ $event->titre }}</h4>
                                    <p class="text-sm text-purple-600">
                                        {{ $event->date->format('d/m/Y H:i') }} • Organisateur : {{ $event->organisateur->prenom }} {{ $event->organisateur->nom }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('events.show', $event) }}" class="px-3 py-1.5 text-sm rounded-lg text-indigo-600 hover:bg-indigo-100 transition">Voir</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-purple-600 text-center py-8">Aucun événement disponible.</p>
                        @endforelse
                    </div>
                @endif

                    {{-- ANALYTICS : uniquement admin --}}
                    @if($activeTab === 'analytics')
                        @if(auth()->user()->role !== 'admin')
                            <div class="bg-white rounded-2xl border border-purple-100 shadow p-8 text-center">
                                <span class="material-symbols-rounded text-5xl text-purple-300">lock</span>
                                <p class="mt-3 text-purple-600">Cette section est réservée aux administrateurs.</p>
                            </div>
                        @else
                            {{-- TON CODE ANALYTICS ICI --}}
                            <div class="space-y-8">
                                <div class="bg-white rounded-2xl border border-purple-100 shadow p-6">
                                    <h3 class="text-lg font-semibold text-purple-900 mb-4">Analyses générales</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        @php
                                            $totalUsers = \App\Models\User::count();
                                            $totalEvents = \App\Models\Evenement::count();
                                            $totalRevenue = \App\Models\Evenement::sum('tarif');
                                            $avgFillRate = $totalEvents > 0
                                                ? round(\App\Models\Evenement::avg('inscrits_count') / \App\Models\Evenement::avg('places_max') * 100, 1)
                                                : 0;
                                        @endphp
                                        <div class="p-4 rounded-xl bg-purple-50 text-purple-700">
                                            <p class="text-sm">Utilisateurs inscrits</p>
                                            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
                                        </div>
                                        <div class="p-4 rounded-xl bg-indigo-50 text-indigo-700">
                                            <p class="text-sm">Événements publiés</p>
                                            <p class="text-2xl font-bold">{{ $totalEvents }}</p>
                                        </div>
                                        <div class="p-4 rounded-xl bg-pink-50 text-pink-700">
                                            <p class="text-sm">Revenu total</p>
                                            <p class="text-2xl font-bold">{{ number_format($totalRevenue) }} FCFA</p>
                                        </div>
                                        <div class="p-4 rounded-xl bg-green-50 text-green-700">
                                            <p class="text-sm">Taux de remplissage moyen</p>
                                            <p class="text-2xl font-bold">{{ $avgFillRate }} %</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-2xl border border-purple-100 shadow p-6">
                                    <h3 class="text-lg font-semibold text-purple-900 mb-4">Inscriptions par mois</h3>
                                    <canvas id="inscriptionsChart" height="100"></canvas>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-white rounded-2xl border border-purple-100 shadow p-6">
                                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Répartition des rôles</h3>
                                        <canvas id="rolesChart"></canvas>
                                    </div>

                                    <div class="bg-white rounded-2xl border border-purple-100 shadow p-6">
                                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Top événements populaires</h3>
                                        <ul class="space-y-3">
                                            @foreach(\App\Models\Evenement::withCount('participants')->orderByDesc('participants_count')->take(3)->get() as $event)
                                                <li class="flex justify-between items-center">
                                                    <span class="text-sm text-purple-800">{{ $event->titre }}</span>
                                                    <span class="px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-700">
                                    {{ $event->participants_count }} inscrits
                                </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                const mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
                                const inscriptions = [12, 19, 23, 45, 67, 89];

                                new Chart(document.getElementById('inscriptionsChart'), {
                                    type: 'line',
                                    data: {
                                        labels: mois,
                                        datasets: [{
                                            label: 'Inscriptions',
                                            data: inscriptions,
                                            borderColor: '#7c3aed',
                                            backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                            tension: 0.4
                                        }]
                                    },
                                    options: { responsive: true, plugins: { legend: { display: false } } }
                                });

                                const roles = {
                                    participant: {{ \App\Models\User::where('role', 'participant')->count() }},
                                    organisateur: {{ \App\Models\User::where('role', 'organisateur')->count() }},
                                    admin: {{ \App\Models\User::where('role', 'admin')->count() }}
                                };

                                new Chart(document.getElementById('rolesChart'), {
                                    type: 'doughnut',
                                    data: {
                                        labels: ['Participants', 'Organisateurs', 'Admins'],
                                        datasets: [{
                                            data: [roles.participant, roles.organisateur, roles.admin],
                                            backgroundColor: ['#a78bfa', '#6366f1', '#ef4444']
                                        }]
                                    },
                                    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
                                });
                            </script>
                        @endif
                    @endif

                    {{-- SETTINGS : uniquement admin --}}
                    @if($activeTab === 'settings')
                        @if(auth()->user()->role !== 'admin')
                            <div class="bg-white rounded-2xl border border-purple-100 shadow p-8 text-center">
                                <span class="material-symbols-rounded text-5xl text-purple-300">lock</span>
                                <p class="mt-3 text-purple-600">Cette section est réservée aux administrateurs.</p>
                            </div>
                        @else
                            {!! $settingsView !!}
                        @endif
                    @endif

            </div><!-- /content -->
        </div>
    </div>
@endsection
