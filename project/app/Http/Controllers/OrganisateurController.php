<?php

namespace App\Http\Controllers;

use App\Models\Organisateur;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class OrganisateurController extends Controller
{
    public function index()
    {
        return response()->json(Organisateur::with('utilisateur')->paginate(15));
    }

    public function show(Organisateur $organisateur)
    {
        return response()->json($organisateur->load('utilisateur'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'contact' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        // Optionally enforce role
        $user = Utilisateur::find($data['utilisateur_id']);
        if ($user && $user->role !== 'organisateur') {
            $user->role = 'organisateur';
            $user->save();
        }

        $organisateur = Organisateur::create($data);
        return response()->json($organisateur->load('utilisateur'), 201);
    }

    public function update(Request $request, Organisateur $organisateur)
    {
        $data = $request->validate([
            'utilisateur_id' => 'sometimes|exists:utilisateurs,id',
            'contact' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
        ]);

        $organisateur->update($data);
        return response()->json($organisateur->load('utilisateur'));
    }

    public function destroy(Organisateur $organisateur)
    {
        $organisateur->delete();
        return response()->json(null, 204);
    }
}
