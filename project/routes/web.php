<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BilletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->get('/profile', function () {
    return view('profile');
})->name('profile');
Route::middleware(['auth'])->get('/billet/{billet}/download', [BilletController::class, 'download'])
    ->name('billet.download');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/analytics/pdf', [AnalyticsController::class, 'pdf'])->name('analytics.pdf');
    Route::get('/analytics/excel', [AnalyticsController::class, 'excel'])->name('analytics.excel');
});

Route::middleware(['auth', 'organisateur'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        $query = $user->role === 'admin'
            ? \App\Models\Evenement::query()
            : \App\Models\Evenement::where('organisateur_id', $user->id);

        $stats = [
            'totalEvents'       => (clone $query)->count(),
            'totalParticipants' => (clone $query)->sum('inscrits_count'),
            'totalRevenue'      => (clone $query)->sum('tarif'),
            'avgAttendance'     => 0,
        ];

        $recentEvents = (clone $query)->orderBy('date', 'desc')->take(5)->get();
        $events       = (clone $query)->orderBy('date', 'desc')->get();

        $allEvents = $user->role === 'admin'
            ? \App\Models\Evenement::with('organisateur')->orderBy('date', 'desc')->get()
            : collect();

        $settingsView = $user->role === 'admin'
            ? view('admin.roles-inline', ['users' => \App\Models\User::orderBy('created_at', 'desc')->get()])
            : view('dashboard.settings-organisateur');

        return view('dashboard', compact('stats', 'recentEvents', 'events', 'allEvents', 'settingsView'));
    })->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('role.update');
});

Route::get('/inscription', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/inscription', [AuthController::class, 'register'])->name('register');

Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/connexion', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// EVENTS

Route::middleware(['auth'])->group(function () {
    Route::get('/events/{event}/export/pdf', [EventController::class, 'exportPdf'])->name('events.export.pdf');
    Route::get('/events/{event}/export/excel', [EventController::class, 'exportExcel'])->name('events.export.excel');
});


Route::get('/events/cards', [EventController::class, 'cards'])
    ->name('events.cards')
    ->middleware('auth');
Route::get('/search', [App\Http\Controllers\EventController::class, 'index'])->name('events.search');

Route::get('/events/search', [EventController::class, 'search'])->name('events.search');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{id}/register', [EventController::class, 'register'])->name('events.register');
Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/events/{evenement}', [EventController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
Route::get('/events/cards', [EventController::class, 'cards'])->name('events.cards');

Route::post('/evenements/{id}/payer', [EventController::class, 'payer'])->name('events.payer');
Route::get('/evenements/{id}/paiement', [EventController::class, 'choixPaiement'])->name('events.choixPaiement');
Route::post('/evenements/{id}/paiement', [EventController::class, 'traiterPaiement'])->name('events.traiterPaiement');
Route::get('/evenements/{id}/inscription/confirmee', [EventController::class, 'registerAfterPayment'])->name('events.register.apres.paiement');
Route::post('/evenements/{id}/payer/stripe', [EventController::class, 'payerStripe'])->name('events.payer');
Route::post('/evenements/{id}/payer/paypal', [EventController::class, 'payerPayPal'])->name('events.payer.paypal');
Route::post('/evenements/{id}/payer/mobile', [EventController::class, 'payerMobile'])->name('events.payer.mobile');

Route::get('/billet/valider/{code}', [BilletController::class, 'valider'])->name('billet.valider');

// Paiement Stripe
Route::post('/events/{id}/create-payment-intent', [EventController::class, 'createPaymentIntent'])->name('events.create-payment-intent');
Route::post('/events/{id}/register', [EventController::class, 'register'])->name('events.register');

// PayPal & Mobile Money
Route::post('/events/{id}/payer/paypal', [EventController::class, 'payerPayPal'])->name('events.payer.paypal');
Route::post('/events/{id}/payer/mobile', [EventController::class, 'payerMobile'])->name('events.payer.mobile');
