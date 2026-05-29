<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\User;

class AdminChildController extends Controller
{
    /**
     * GET /api/admin/children
     * Ambil semua data anak beserta informasi orang tua (untuk admin).
     */
    public function index()
    {
        $children = Child::with('user:id,name,email')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $children,
            'total'  => $children->count(),
        ]);
    }

    /**
     * GET /api/admin/users/{id}/children
     * Ambil semua anak milik user tertentu (untuk admin).
     */
    public function byUser(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan.'], 404);
        }

        $children = Child::where('user_id', $id)->latest()->get();

        return response()->json([
            'status' => 'success',
            'user'   => $user->only(['id', 'name', 'email', 'role', 'created_at']),
            'data'   => $children,
            'total'  => $children->count(),
        ]);
    }
}
