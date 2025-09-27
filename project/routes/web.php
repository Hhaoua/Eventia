<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;



Route::get('/', [HomeController::class, 'index'])->name('home');


 Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/inscription', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/inscription', [AuthController::class, 'register'])->name('register');

Route::get('/connexion', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/connexion', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//EVENTS
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


