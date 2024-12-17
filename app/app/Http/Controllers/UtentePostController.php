<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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
        $query = User::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
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
}
