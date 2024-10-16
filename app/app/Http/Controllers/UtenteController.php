<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class UtenteController extends Controller
{
    public function index()
    {
        return Inertia::render('PrimevueUtenti', [
            'pageTitle' => 'Utenti'
        ]);
    }
}
