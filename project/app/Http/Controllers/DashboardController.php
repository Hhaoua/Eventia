<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Tous les événements de l'organisateur
        $events = Evenement::where('organisateur_id', $user->id)->get();

        // Statistiques
        $totalEvents = $events->count();
        $totalParticipants = $events->sum(function($event) {
            return $event->participants()->count();
        });
        $totalRevenue = $events->sum(function($event) {
            if($event->type === 'payant') {
                return $event->participants()->count() * $event->tarif;
            }
            return 0;
        });
        $avgAttendance = $totalEvents > 0 ? round($totalParticipants / $totalEvents) : 0;

        $stats = [
            'totalEvents' => $totalEvents,
            'totalParticipants' => $totalParticipants,
            'totalRevenue' => $totalRevenue,
            'avgAttendance' => $avgAttendance,
        ];

        // 5 événements récents
        $recentEvents = $events->sortByDesc('date')->take(5);

        return view('dashboard', compact('stats', 'recentEvents', 'events'));
    }
}
