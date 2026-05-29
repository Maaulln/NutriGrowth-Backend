<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * GET /api/foods
     * Mengambil daftar semua makanan beserta informasi nutrisinya.
     * Mendukung dua query parameter opsional:
     *   - ?category=xxx  → filter makanan berdasarkan kategori
     *   - ?search=xxx    → cari makanan berdasarkan nama (pencarian sebagian kata)
     * Endpoint ini terbuka untuk umum, tidak memerlukan autentikasi.
     */
    public function index(Request $request)
    {
        $query = Food::query();

        // Filter berdasarkan kategori jika parameter ?category dikirim
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Cari berdasarkan nama jika parameter ?search dikirim
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $foods = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar makanan berhasil diambil',
            'data' => $foods
        ]);
    }

    /**
     * GET /api/foods/{id}
     */
    public function show($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Makanan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail makanan berhasil diambil',
            'data'    => $food,
        ]);
    }

    /**
     * POST /api/admin/foods  — tambah makanan baru (admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category'         => 'required|string|max:100',
            'calories'         => 'required|numeric|min:0',
            'protein'          => 'required|numeric|min:0',
            'fat'              => 'required|numeric|min:0',
            'carbs'            => 'required|numeric|min:0',
            'price_per_serving'=> 'nullable|integer|min:0',
            'serving_size'     => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'image_url'        => 'nullable|url|max:500',
        ]);

        $food = Food::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Makanan berhasil ditambahkan',
            'data'    => $food,
        ], 201);
    }

    /**
     * PUT /api/admin/foods/{id}  — update makanan (admin only)
     */
    public function update(Request $request, $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Makanan tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'category'         => 'required|string|max:100',
            'calories'         => 'required|numeric|min:0',
            'protein'          => 'required|numeric|min:0',
            'fat'              => 'required|numeric|min:0',
            'carbs'            => 'required|numeric|min:0',
            'price_per_serving'=> 'nullable|integer|min:0',
            'serving_size'     => 'nullable|string|max:100',
            'description'      => 'nullable|string',
            'image_url'        => 'nullable|url|max:500',
        ]);

        $food->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Makanan berhasil diperbarui',
            'data'    => $food,
        ]);
    }

    /**
     * DELETE /api/admin/foods/{id}  — hapus makanan (admin only)
     */
    public function destroy($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Makanan tidak ditemukan',
            ], 404);
        }

        $food->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Makanan berhasil dihapus',
        ]);
    }
}
