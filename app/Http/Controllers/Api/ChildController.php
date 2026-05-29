<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildController extends Controller
{
    /**
     * GET /api/children
     * Mengambil semua data anak milik user yang sedang login.
     * Data diurutkan dari yang terbaru. Hanya anak milik user sendiri yang ditampilkan.
     */
    public function index()
    {
        $children = Auth::user()->children()->latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar anak berhasil diambil',
            'data' => $children
        ]);
    }

    /**
     * POST /api/children
     * Menyimpan data anak baru milik user yang sedang login.
     * Field wajib: name, gender (male/female), birth_date.
     * Field opsional: weight_kg, height_cm, muac_cm (lingkar lengan atas).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'weight_kg' => 'nullable|numeric|min:0|max:100',
            'height_cm' => 'nullable|numeric|min:0|max:250',
            'muac_cm' => 'nullable|numeric|min:0|max:100',
        ]);

        $child = Auth::user()->children()->create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data anak berhasil ditambahkan',
            'data' => $child
        ], 201);
    }

    /**
     * GET /api/children/{id}
     * Menampilkan detail satu data anak berdasarkan ID.
     * Hanya bisa mengakses anak milik user sendiri — jika bukan miliknya, dikembalikan 404.
     */
    public function show($id)
    {
        $child = Auth::user()->children()->find($id);

        if (!$child) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail anak berhasil diambil',
            'data' => $child
        ]);
    }

    /**
     * PUT/PATCH /api/children/{id}
     * Memperbarui data anak berdasarkan ID.
     * Semua field bersifat opsional (pakai 'sometimes'), hanya field yang dikirim yang diubah.
     * Hanya bisa mengubah anak milik user sendiri.
     */
    public function update(Request $request, $id)
    {
        $child = Auth::user()->children()->find($id);

        if (!$child) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'gender' => 'sometimes|in:male,female',
            'birth_date' => 'sometimes|date',
            'weight_kg' => 'sometimes|nullable|numeric|min:0|max:100',
            'height_cm' => 'sometimes|nullable|numeric|min:0|max:250',
            'muac_cm' => 'sometimes|nullable|numeric|min:0|max:100',
        ]);

        $child->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data anak berhasil diperbarui',
            'data' => $child
        ]);
    }

    /**
     * DELETE /api/children/{id}
     * Menghapus data anak berdasarkan ID secara permanen.
     * Hanya bisa menghapus anak milik user sendiri.
     */
    public function destroy($id)
    {
        $child = Auth::user()->children()->find($id);

        if (!$child) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data anak tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        $child->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data anak berhasil dihapus'
        ]);
    }
}
