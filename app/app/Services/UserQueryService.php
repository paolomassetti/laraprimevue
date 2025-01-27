<?php
namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserQueryService
{
    public function buildQuery(Request $request)
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

        if ($request->filled('sortField') && $request->filled('sortOrder')) {
            $sortField = $request->input('sortField');
            $sortOrder = $request->input('sortOrder') == 1 ? 'desc' : 'asc';
            $query->orderBy($sortField, $sortOrder);
        }

        return $query;
    }
}
