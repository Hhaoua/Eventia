<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index()
    {
        return response()->json(Participant::paginate(15));
    }

    public function show(Participant $participant)
    {
        return response()->json($participant);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email',
        ]);
        $participant = Participant::create($data);
        return response()->json($participant, 201);
    }

    public function update(Request $request, Participant $participant)
    {
        $data = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:participants,email,' . $participant->id,
        ]);
        $participant->update($data);
        return response()->json($participant);
    }

    public function destroy(Participant $participant)
    {
        $participant->delete();
        return response()->json(null, 204);
    }
}
