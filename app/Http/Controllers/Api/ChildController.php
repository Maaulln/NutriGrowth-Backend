<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildController extends Controller
{
    /**
     * Display a listing of the children for the authenticated user.
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
     * Store a newly created child in storage.
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
     * Display the specified child.
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
     * Update the specified child in storage.
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
     * Remove the specified child from storage.
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
