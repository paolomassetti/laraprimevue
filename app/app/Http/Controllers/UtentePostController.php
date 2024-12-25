<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtenteRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UtentePostController extends Controller
{
    public function index()
    {
        return Inertia::render('utente/index', [
            'pageTitle' => 'Utenti post',
        ]);
    }

    public function search(Request $request)
    {
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->filled('created_at')) {

            $date = Carbon::parse($request->input('created_at'), 'UTC')->format('Y-m-d');

            $query->whereBetween('created_at', [
                $date . ' 00:00:00',
                $date . ' 23:59:59'
            ]);
        }

        $totalData = $query->count();

        if ($request->filled('sortField') && $request->filled('sortOrder')) {
            $sortField = $request->input('sortField');
            $sortOrder = $request->input('sortOrder') == 1 ? 'asc' : 'desc';
            $query->orderBy($sortField, $sortOrder);
        }

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $data = $query->offset($start)->limit($length)->get();

        return response()->json([
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => UserResource::collection($data)
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('utente/edit', [
            'pageTitle' => 'Modifica utente',
            'user' => $user,
        ]);
    }

    public function update(UtenteRequest $request, User $user)
    {
        $user->update($request->validated());
        return redirect()->route('utenti.post')->with('success', 'utente aggiornato con successo');
    }
}
