<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UtenteController;
use App\Http\Controllers\UtentePostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/utenti', [UtenteController::class, 'index'])->name('utenti.index');
    Route::get('/utenti-post', [UtentePostController::class, 'index'])->name('utenti.post');
    Route::post('/utenti/search', [UtentePostController::class, 'search'])->name('utenti.search');
    Route::get('/utente/edit/{user}', [UtentePostController::class, 'edit'])->name('utente.edit');
    Route::put('/utente/update/{user}', [UtentePostController::class, 'update'])->name('utente.update');

    Route::get('/chart', function () {
        return Inertia::render('PrimevueChart');
    });
});

require __DIR__.'/auth.php';
