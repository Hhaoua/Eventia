<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\OrganisateurController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\PaiementController;

Route::apiResource('utilisateurs', UtilisateurController::class);
Route::apiResource('organisateurs', OrganisateurController::class);
Route::apiResource('participants', ParticipantController::class);
Route::apiResource('evenements', EvenementController::class);
Route::apiResource('paiements', PaiementController::class);
