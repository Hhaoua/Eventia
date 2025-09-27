<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    public function index()
    {
        return response()->json(Paiement::orderBy('date', 'desc')->paginate(15));
    }

    public function show(Paiement $paiement)
    {
        return response()->json($paiement);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'montant' => 'required|numeric|min:0',
            'mode' => 'required|string|max:100',
            'statut' => 'nullable|string|max:100',
            'date' => 'required|date',
            'id_inscription' => 'nullable|integer',
        ]);
        $paiement = Paiement::create($data);
        return response()->json($paiement, 201);
    }

    public function update(Request $request, Paiement $paiement)
    {
        $data = $request->validate([
            'montant' => 'sometimes|numeric|min:0',
            'mode' => 'sometimes|string|max:100',
            'statut' => 'nullable|string|max:100',
            'date' => 'sometimes|date',
            'id_inscription' => 'nullable|integer',
        ]);
        $paiement->update($data);
        return response()->json($paiement);
    }

    public function destroy(Paiement $paiement)
    {
        $paiement->delete();
        return response()->json(null, 204);
    }
}
