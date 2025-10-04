<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function roles()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.roles-inline', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'in:participant,organisateur,admin']);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Rôle mis à jour.');
    }
}
