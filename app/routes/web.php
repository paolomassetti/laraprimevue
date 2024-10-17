<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtenteController;
use App\Http\Controllers\UtentePostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/utenti', [UtenteController::class, 'index'])->name('utenti.index');
    Route::get('/utenti/post', [UtentePostController::class, 'index'])->name('utenti.post');
    Route::post('/utente/search', [UtentePostController::class, 'search'])->name('utente.search');

    Route::get('/table', function () {
        return Inertia::render('PrimevueTable');
    });

    Route::get('/chart', function () {
        return Inertia::render('PrimevueChart');
    });
});

require __DIR__.'/auth.php';
