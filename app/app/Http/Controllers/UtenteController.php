<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UtenteController extends Controller
{
    public function index()
    {
        return Inertia::render('PrimevueUtenti', [
            'pageTitle' => 'Utenti',
        ]);
    }

    public function apiIndex()
    {
        $users = User::all();
        return response()->json($users);
    }
}
