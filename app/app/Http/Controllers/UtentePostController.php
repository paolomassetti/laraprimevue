<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtenteRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            $sortOrder = $request->input('sortOrder') == 1 ? 'desc' : 'asc';
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

    public function create()
    {
        return Inertia::render('utente/new', [
            'pageTitle' => 'Crea utente',
        ]);
    }

    public function store(UtenteRequest $request)
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make('password123'),
            'deleted_at' => null,
        ]);

        return redirect()
                    ->route('utenti.post')
                    ->with('success', 'utente creato con successo');

    }

    public function edit(User $user)
    {
        return Inertia::render('utente/edit', [
            'pageTitle' => 'Modifica utente',
            'user' => $user,
            'backUrl' => route('utenti.post'),
        ]);
    }

    public function update(UtenteRequest $request, User $user)
    {
        $user->update($request->validated());
        return redirect()->route('utenti.post')->with('success', 'utente aggiornato con successo');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('utenti.post')->with('success', 'utente eliminato con successo');
    }

    public function export()
    {
        
        return;
    }
}
