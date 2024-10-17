<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UtentePostController extends Controller
{
    public function index()
    {
        return Inertia::render('PrimevueUtentiPost', [
            'pageTitle' => 'Utenti post',
        ]);
    }

    public function search(Request $request)
    {
        $query = User::all();
        return;
    }
}
