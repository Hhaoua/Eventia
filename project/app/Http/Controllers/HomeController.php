<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evenement;

class HomeController extends Controller
{
    public function index()
    {
        // On récupère les 8 événements les plus récents
        $events = Evenement::orderBy('date', 'asc')->take(8)->get();

        // On peut aussi filtrer les événements "en vedette"
        $featuredEvents = $events->filter(fn($event) => $event->featured)->take(3);

        return view('home', compact('events', 'featuredEvents'));
    }
}
