<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * GET /api/users
     * Ambil semua data user
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get(['id', 'name', 'email', 'created_at', 'updated_at']);
        return response()->json([
            'data'  => $users,
            'total' => $users->count(),
        ]);
    }

    /**
     * POST /api/users
     * Tambah user baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first('email');
            if ($firstError && str_contains($firstError, 'taken')) {
                return response()->json(['error' => 'Email sudah terdaftar.'], 409);
            }
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt(str()->random(16)), // random password, tidak digunakan
        ]);

        return response()->json([
            'message' => 'User berhasil ditambahkan!',
            'data'    => $user->only(['id', 'name', 'email', 'created_at', 'updated_at']),
        ], 201);
    }

    /**
     * GET /api/users/{id}
     * Ambil satu user
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        return response()->json(['data' => $user->only(['id', 'name', 'email', 'created_at', 'updated_at'])]);
    }

    /**
     * PUT /api/users/{id}
     * Update data user
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Data user berhasil diperbarui.',
            'data'    => $user->fresh()->only(['id', 'name', 'email', 'created_at', 'updated_at']),
        ]);
    }

    /**
     * DELETE /api/users/{id}
     * Hapus user
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
