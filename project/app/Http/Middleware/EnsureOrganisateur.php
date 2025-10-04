<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOrganisateur
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'participant') {
            abort(403, 'Accès réservé aux organisateurs.');
        }

        return $next($request);
    }
}
