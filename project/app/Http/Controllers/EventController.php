<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Evenement;
use App\Mail\InscriptionEventMail;
use Illuminate\Support\Facades\Mail;
use App\Services\BilletService;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantsExport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class EventController extends Controller
{
    use AuthorizesRequests;
    public function __construct(BilletService $billetService)
    {
        $this->billetService = $billetService;
    }
    // Tableau de bord
    public function index(Request $request)
    {
        $user = Auth::user();

        // Statistiques de base
        $stats = [
            'totalEvents' => Evenement::where('organisateur_id', $user->id)->count(),
            'totalParticipants' => Evenement::where('organisateur_id', $user->id)->sum('inscrits_count'),
            'totalRevenue' => Evenement::where('organisateur_id', $user->id)->sum('prix_total'),
            'avgAttendance' => Evenement::where('organisateur_id', $user->id)->avg('taux_participation'),
        ];

        $recentEvents = Evenement::where('organisateur_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        // Recherche + filtres
        $query = Evenement::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('lieu', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('dateFrom')) {
            $query->where('date', '>=', $request->dateFrom);
        }

        if ($request->filled('dateTo')) {
            $query->where('date', '<=', $request->dateTo);
        }

        // Résultats
        $events = $query->orderBy('date', 'desc')->get();
        $featuredEvents = $events->where('featured', true)->take(3);

        return view('home', compact('stats', 'recentEvents', 'events', 'featuredEvents'));
    }

    public function create()
    {
        $categories = ['Conférence', 'Atelier', 'Webinaire', 'Sommet', 'Autre'];
        return view('events.create', compact('categories'));
    }
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


    public function show($id)
    {
        $event = Evenement::findOrFail($id);

        return view('events.show', compact('event'));
    }

    public function edit(Evenement $event)
    {
        $categories = ['Conférence', 'Atelier', 'Webinaire', 'Sommet', 'Autre'];
        return view('events.edit', ['evenement' => $event, 'categories' => $categories]);
    }

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

    public function destroy(Evenement $evenement)
    {
        $evenement->delete();
        return redirect()->route('events.index')->with('success', 'Événement supprimé.');
    }

    public function cards(Request $request)
    {
        $query = Evenement::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('dateFrom')) {
            $query->where('date', '>=', $request->dateFrom);
        }

        if ($request->filled('dateTo')) {
            $query->where('date', '<=', $request->dateTo);
        }

        $events = $query->orderBy('date', 'desc')->get();

        return view('partials.event-cards', compact('events'));
    }
    public function exportPdf(Evenement $event)
    {
        if (auth()->id() !== $event->organisateur_id) {
            abort(403, 'Action non autorisée.');
        }        $participants = $event->participants()->get();

        $pdf = Pdf::loadView('exports.participants_pdf', [
            'event' => $event,
            'participants' => $participants
        ]);

        return $pdf->download('participants-' . $event->titre . '.pdf');
    }

    public function exportExcel(Evenement $event)
    {
        if (auth()->id() !== $event->organisateur_id) {
            abort(403, 'Action non autorisée.');
        }        return Excel::download(new ParticipantsExport($event), 'participants-' . $event->titre . '.xlsx');
    }
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

        if ($event->participants()->where('user_id', $user->id)->exists()) {
            return redirect()->route('events.show', $event->id)
                ->with('info', 'Vous êtes déjà inscrit à cet événement.');
        }

        if ($event->inscrits_count >= $event->places_max) {
            return redirect()->route('events.show', $event->id)
                ->with('error', 'Désolé, cet événement est complet.');
        }

        $event->participants()->attach($user->id);
        $event->increment('inscrits_count');

        $billet = $this->billetService->genererBillet($user, $event);

        Mail::to($user->email)->send(new InscriptionEventMail($user, $event, $billet));

        return redirect()->route('events.show', $event->id)
            ->with('success', 'Inscription réussie ✅ Un e-mail avec votre billet vous a été envoyé.');
    }

    // App\Http\Controllers\EventController.php

    public function choixPaiement($id)
    {
        $event = Evenement::findOrFail($id);

        if ($event->type !== 'payant') {
            return redirect()->route('events.register', $id);
        }

        return view('events.choix-paiement', compact('event'));
    }


    public function traiterPaiement(Request $request, $id)
{
    $event = Evenement::findOrFail($id);
    $user = auth()->user();
    $moyen = $request->input('moyen_paiement');

    Stripe::setApiKey(config('services.stripe.secret'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Inscription : ' . $event->titre,
                ],
                'unit_amount' => $event->tarif * 100, // centimes
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('events.register.apres.paiement', $id) . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => route('events.show', $id) . '?cancelled=1',
        'metadata' => [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'moyen_paiement' => $moyen,
        ],
    ]);

    return redirect($session->url);
}

    public function registerAfterPayment(Request $request, $id)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('events.show', $id)->with('error', 'Paiement annulé ou invalide.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = \Stripe\Checkout\Session::retrieve($sessionId);

        if ($session->payment_status !== 'payant') {
            return redirect()->route('events.show', $id)->with('error', 'Paiement non confirmé.');
        }

        $user = auth()->user();
        $event = Evenement::findOrFail($id);

        if ($event->participants()->where('user_id', $user->id)->exists()) {
            return redirect()->route('events.show', $event->id)->with('info', 'Déjà inscrit.');
        }

        if ($event->inscrits_count >= $event->places_max) {
            return redirect()->route('events.show', $event->id)->with('error', 'Complet.');
        }

        $event->participants()->attach($user->id);
        $event->increment('inscrits_count');

        $billet = app(BilletService::class)->genererBillet($user, $event);

        Paiement::create([
            'montant' => $event->tarif,
            'mode' => $session->metadata->moyen_paiement ?? 'stripe',
            'statut' => 'succes',
            'date' => now(),
            'id_inscription' => $billet->id,
        ]);

        Mail::to($user->email)->send(new InscriptionEventMail($user, $event, $billet));

        return redirect()->route('events.show', $event->id)
            ->with('success', '✅ Paiement réussi & inscription confirmée ! Votre billet vous a été envoyé.');
    }

    public function payer(Request $request, $id)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $event = Evenement::findOrFail($id);
        $user = auth()->user();

        try {
            $intent = PaymentIntent::create([
                'amount' => $event->tarif * 100,
                'currency' => 'eur',
                'payment_method' => $request->payment_method_id,
                'confirm' => true,
                'metadata' => [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                ],
            ]);

            if ($intent->status === 'succeeded') {
                // Inscription + billet
                $event->participants()->attach($user->id);
                $event->increment('inscrits_count');
                $billet = app(BilletService::class)->genererBillet($user, $event);

                Paiement::create([
                    'montant' => $event->tarif,
                    'mode' => 'stripe',
                    'statut' => 'succes',
                    'date' => now(),
                    'id_inscription' => $billet->id,
                ]);

                Mail::to($user->email)->send(new InscriptionEventMail($user, $event, $billet));

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Paiement non abouti.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }



// -----------------------------
// 1. STRIPE
// -----------------------------
    public function payerStripe(Request $request, $id)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $event = Evenement::findOrFail($id);
        $user = auth()->user();

        try {
            $intent = PaymentIntent::create([
                'amount' => $event->tarif * 100,
                'currency' => 'eur',
                'payment_method' => $request->payment_method_id,
                'confirm' => true,
                'metadata' => [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                ],
            ]);

            if ($intent->status === 'succeeded') {
                return $this->finaliserInscription($event, $user, 'stripe');
            }

            return response()->json(['success' => false, 'message' => 'Paiement non abouti.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

// -----------------------------
// 2. PAYPAL
// -----------------------------
    public function payerPayPal(Request $request, $id)
    {
        $event = Evenement::findOrFail($id);
        $user = auth()->user();
        $orderId = $request->order_id;

        // Vérifier la commande PayPal via l'API (côté serveur)
        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://api-m.sandbox.paypal.com/v2/checkout/orders/{$orderId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getPayPalAccessToken(),
                'Content-Type' => 'application/json',
            ],
        ]);

        $order = json_decode($response->getBody(), true);

        if ($order['status'] === 'COMPLETED') {
            return $this->finaliserInscription($event, $user, 'paypal');
        }

        return response()->json(['success' => false, 'message' => 'Commande PayPal non valide.']);
    }

    private function getPayPalAccessToken()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://api-m.sandbox.paypal.com/v1/oauth2/token', [
            'auth' => [config('services.paypal.client_id'), config('services.paypal.secret')],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['access_token'];
    }

// -----------------------------
// 3. MOBILE MONEY
// -----------------------------
    public function payerMobile(Request $request, $id)
    {
        $event = Evenement::findOrFail($id);
        $user = auth()->user();

        // Simulation de paiement Mobile Money
        // Tu peux ici appeler une API locale (Orange, MTN, Moov)
        $operateur = $request->operateur;
        $phone = $request->phone;

        // Exemple : générer un token ou un ID de transaction
        $transactionId = 'MM-' . strtoupper(bin2hex(random_bytes(4)));

        // Simuler succès (tu peux remplacer par un appel réel)
        $succes = true;

        if ($succes) {
            return $this->finaliserInscription($event, $user, 'mobile_money');
        }

        return response()->json(['success' => false, 'message' => 'Paiement Mobile Money échoué.']);
    }

// -----------------------------
// FINALISATION COMMUNE
// -----------------------------
    private function finaliserInscription($event, $user, $mode)
    {
        if ($event->participants()->where('user_id', $user->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Déjà inscrit.']);
        }

        if ($event->inscrits_count >= $event->places_max) {
            return response()->json(['success' => false, 'message' => 'Événement complet.']);
        }

        $event->participants()->attach($user->id);
        $event->increment('inscrits_count');

        $billet = app(BilletService::class)->genererBillet($user, $event);

        Paiement::create([
            'montant' => $event->tarif,
            'mode' => $mode,
            'statut' => 'succes',
            'date' => now(),
            'id_inscription' => $billet->id,
        ]);

        Mail::to($user->email)->send(new InscriptionEventMail($user, $event, $billet));

        return response()->json(['success' => true]);
    }

}


