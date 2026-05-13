<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Food::query();

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Search by name
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
     * Display the specified resource.
     */
    public function show($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'status' => 'error',
                'message' => 'Makanan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Detail makanan berhasil diambil',
            'data' => $food
        ]);
    }
}
