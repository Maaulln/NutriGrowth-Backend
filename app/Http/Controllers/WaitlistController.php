<?php

namespace App\Http\Controllers;

use App\Models\Waitlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WaitlistController extends Controller
{
    /**
     * GET /api/waitlist
     * Ambil semua data waitlist
     */
    public function index()
    {
        $waitlists = Waitlist::orderBy('created_at', 'desc')->get();
        return response()->json([
            'data' => $waitlists,
            'total' => $waitlists->count(),
        ]);
    }

    /**
     * POST /api/waitlist
     * Tambah data baru ke waitlist
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:waitlists',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first('email');
            if ($firstError && str_contains($firstError, 'taken')) {
                return response()->json(['error' => 'Email sudah terdaftar di waitlist.'], 409);
            }
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $waitlist = Waitlist::create([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Berhasil mendaftar ke waitlist!',
            'data'    => $waitlist,
        ], 201);
    }

    /**
     * GET /api/waitlist/{id}
     * Ambil satu data waitlist
     */
    public function show(string $id)
    {
        $waitlist = Waitlist::find($id);

        if (!$waitlist) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json(['data' => $waitlist]);
    }

    /**
     * PUT /api/waitlist/{id}
     * Update data waitlist
     */
    public function update(Request $request, string $id)
    {
        $waitlist = Waitlist::find($id);

        if (!$waitlist) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:waitlists,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $waitlist->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Data berhasil diperbarui.',
            'data'    => $waitlist->fresh(),
        ]);
    }

    /**
     * DELETE /api/waitlist/{id}
     * Hapus data waitlist
     */
    public function destroy(string $id)
    {
        $waitlist = Waitlist::find($id);

        if (!$waitlist) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $waitlist->delete();

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
