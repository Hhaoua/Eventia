<?php

namespace App\Http\Controllers;

use App\Services\BilletService;
use Illuminate\Http\Request;

class BilletController extends Controller
{
    protected $billetService;

    public function __construct(BilletService $billetService)
    {
        $this->billetService = $billetService;
    }

    public function valider($code)
    {
        $resultat = $this->billetService->validerBillet($code);

        if ($resultat['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Billet valide',
                'billet' => $resultat['billet']->load(['user', 'evenement'])
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $resultat['message']
        ], 400);
    }

    public function download(\App\Models\Billet $billet)
    {
        // Sécurité : le billet doit appartenir à l’utilisateur connecté
        abort_if($billet->user_id !== auth()->id(), 403);

        // Chemin vers le fichier PDF (ajuste selon ton stockage)
        $path = storage_path('app/' . $billet->file_path);

        if (!file_exists($path)) {
            abort(404, 'Fichier introuvable.');
        }

        return response()->download($path, 'billet-' . $billet->code . '.pdf');
    }
}
