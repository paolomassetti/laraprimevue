<?php

namespace App\Http\Controllers;

use App\Http\Requests\UtenteRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserQueryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UtentePostController extends Controller
{
    public function index()
    {
        return Inertia::render('utente/index', [
            'pageTitle' => 'Utenti post',
        ]);
    }

    public function search(Request $request, UserQueryService $UserQueryService)
    {
        $query = $UserQueryService->buildQuery($request);

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

    public function export(Request $request, UserQueryService $UserQueryService)
    {

        $query = $UserQueryService->buildQuery($request);

        $users = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nome');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Data Creazione');

        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->name);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, $user->created_at->format('d/m/Y'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'utenti_export.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);

    }
}
