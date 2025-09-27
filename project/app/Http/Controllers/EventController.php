<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Evenement;
use App\Mail\InscriptionEventMail;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{    
    // Tableau de bord
    public function index()
    {
        $user = Auth::user();

        // Statistiques de base
        $stats = [
            'totalEvents' => Evenement::where('organisateur_id', $user->id)->count(),
            'totalParticipants' => Evenement::where('organisateur_id', $user->id)->sum('inscrits_count'),
            'totalRevenue' => Evenement::where('organisateur_id', $user->id)->sum('prix_total'),
            'avgAttendance' => Evenement::where('organisateur_id', $user->id)->avg('taux_participation'),
        ];

        // Récents événements
        $recentEvents = Evenement::where('organisateur_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Tous les événements
        $events = Evenement::where('organisateur_id', $user->id)->get();
         $featuredEvents = $events->filter(fn($event) => $event->featured)->take(3);

         $query = Evenement::query();

    // Filtrer par recherche
    if ($request->has('search') && $request->search) {
        $query->where('titre', 'like', "%{$request->search}%")
              ->orWhere('description', 'like', "%{$request->search}%");
    }

    // Filtrer par catégorie
    if ($request->has('category') && $request->category) {
        $query->where('category', $request->category);
    }

    // Filtrer par type
    if ($request->has('type') && $request->type) {
        $query->where('type', $request->type);
    }

    // Filtrer par date de début
    if ($request->has('dateFrom') && $request->dateFrom) {
        $query->where('date', '>=', $request->dateFrom);
    }

    // Filtrer par date de fin
    if ($request->has('dateTo') && $request->dateTo) {
        $query->where('date', '<=', $request->dateTo);
    }

    $events = $query->orderBy('date', 'desc')->get();


        return view('home', compact('stats', 'recentEvents', 'events','featuredEvents'));
    }

    // Formulaire de création
    public function create()
    {
        $categories = ['Conférence', 'Atelier', 'Webinaire', 'Sommet', 'Autre'];
        return view('events.create', compact('categories'));
    }

    // Enregistrer un nouvel événement
    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'shortDescription' => 'required|string|max:150',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'lieu' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'type' => 'required|in:payant,gratuit',
            'tarif' => 'nullable|numeric|min:0',
            'places_max' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $data['image_url'] = Storage::url($imagePath);
        } else {
            $data['image_url'] = 'https://via.placeholder.com/800x400';
        }

        // Fusion date + time en datetime
        $data['date'] = $data['date'] . ' ' . $data['time'];
        unset($data['time']);

        // Ajout des champs supplémentaires
        $data['organisateur_id'] = $user->id;
        $data['inscrits_count'] = 0;
        $data['statut'] = 'published';

        $evenement = Evenement::create($data);

        return redirect()->route('events.index')->with('success', 'Événement créé avec succès.');
    }
    

    // Afficher un événement
    public function show($id)
    {
        $event = Evenement::findOrFail($id);

        return view('events.show', compact('event'));
    }

    // Formulaire d'édition
   public function edit(Evenement $event)
{
    $categories = ['Conférence', 'Atelier', 'Webinaire', 'Sommet', 'Autre'];
    return view('events.edit', ['evenement' => $event, 'categories' => $categories]);
}


    // Mettre à jour un événement
    public function update(Request $request, Evenement $evenement)
    {
        $data = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'shortDescription' => 'sometimes|string|max:150',
            'description' => 'sometimes|string',
            'date' => 'sometimes|date',
            'time' => 'sometimes',
            'lieu' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:100',
            'type' => 'sometimes|in:payant,gratuit',
            'tarif' => 'nullable|numeric|min:0',
            'places_max' => 'sometimes|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
            $data['image_url'] = Storage::url($imagePath);
        }

        if (isset($data['time']) && isset($data['date'])) {
            $data['date'] = $data['date'] . ' ' . $data['time'];
            unset($data['time']);
        }

        $evenement->update($data);

        return redirect()->route('events.index')->with('success', 'Événement mis à jour avec succès.');
    }

    // Supprimer un événement
    public function destroy(Evenement $evenement)
    {
        $evenement->delete();
        return redirect()->route('events.index')->with('success', 'Événement supprimé.');
    }

    // Recherche
    public function search(Request $request)
    {
        $query = $request->input('q');

        $events = Evenement::query()
            ->where('titre', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        return view('events.index', compact('events'));
    }


   public function register($id)
    {
        $event = Evenement::findOrFail($id);

        if (!auth()->check()) {
            return redirect()->route('login.form')->with('warning', 'Vous devez être connecté pour vous inscrire.');
        }

        $user = auth()->user();

        // Vérifie si l’utilisateur est déjà inscrit
        if ($event->participants()->where('user_id', $user->id)->exists()) {
            return redirect()->route('events.show', $event->id)
                ->with('info', 'Vous êtes déjà inscrit à cet événement.');
        }

        // Vérifie si l'événement n'est pas complet
        if ($event->inscrits_count >= $event->places_max) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Désolé, cet événement est complet.');
        }

        // Ajout de l’utilisateur aux participants
        $event->participants()->attach($user->id);

        // Incrémente le nombre d’inscrits
        $event->increment('inscrits_count');


    // Après l’inscription réussie
    Mail::to($user->email)->send(new InscriptionEventMail($user, $event));

    return redirect()->route('events.show', $event->id)
        ->with('success', 'Inscription réussie ✅ Un e-mail de confirmation vous a été envoyé.');
        }


}
