<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MealPlan;
use App\Models\MealPlanItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlanController extends Controller
{
    /**
     * GET /api/meal-plans
     * Daftar rencana makan milik user, opsional filter ?child_id=
     */
    public function index(Request $request): JsonResponse
    {
        $query = Auth::user()->mealPlans()
            ->with(['child:id,name', 'items.food:id,name,category,calories,protein,fat,carbs,image_url'])
            ->latest('plan_date');

        if ($request->has('child_id')) {
            $query->where('child_id', (int) $request->query('child_id'));
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->get(),
        ]);
    }

    /**
     * POST /api/meal-plans
     * Buat rencana makan baru.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'child_id'  => 'nullable|integer|exists:children,id',
            'plan_date' => 'required|date',
            'title'     => 'required|string|max:255',
            'notes'     => 'nullable|string',
        ]);

        if (!empty($validated['child_id'])) {
            $child = Auth::user()->children()->find($validated['child_id']);
            if (!$child) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data anak tidak ditemukan atau Anda tidak memiliki akses',
                ], 404);
            }
        }

        $plan = Auth::user()->mealPlans()->create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Rencana makan berhasil dibuat',
            'data'    => $plan->load(['child:id,name', 'items']),
        ], 201);
    }

    /**
     * GET /api/meal-plans/{id}
     * Detail satu rencana makan beserta semua item makanannya.
     */
    public function show($id): JsonResponse
    {
        $plan = Auth::user()->mealPlans()
            ->with([
                'child:id,name,gender,birth_date',
                'items.food:id,name,category,calories,protein,fat,carbs,fiber_g,serving_size,image_url',
            ])
            ->find($id);

        if (!$plan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Rencana makan tidak ditemukan atau Anda tidak memiliki akses',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $plan,
        ]);
    }

    /**
     * PUT /api/meal-plans/{id}
     * Update judul, tanggal, catatan, atau anak dari rencana makan.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $plan = Auth::user()->mealPlans()->find($id);

        if (!$plan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Rencana makan tidak ditemukan atau Anda tidak memiliki akses',
            ], 404);
        }

        $validated = $request->validate([
            'child_id'  => 'sometimes|nullable|integer|exists:children,id',
            'plan_date' => 'sometimes|date',
            'title'     => 'sometimes|string|max:255',
            'notes'     => 'sometimes|nullable|string',
        ]);

        if (!empty($validated['child_id'])) {
            $child = Auth::user()->children()->find($validated['child_id']);
            if (!$child) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data anak tidak ditemukan atau Anda tidak memiliki akses',
                ], 404);
            }
        }

        $plan->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Rencana makan berhasil diperbarui',
            'data'    => $plan->load(['child:id,name', 'items.food:id,name,category,calories,protein,fat,carbs,image_url']),
        ]);
    }

    /**
     * DELETE /api/meal-plans/{id}
     * Hapus rencana makan beserta semua itemnya.
     */
    public function destroy($id): JsonResponse
    {
        $plan = Auth::user()->mealPlans()->find($id);

        if (!$plan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Rencana makan tidak ditemukan atau Anda tidak memiliki akses',
            ], 404);
        }

        $plan->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Rencana makan berhasil dihapus',
        ]);
    }

    /**
     * POST /api/meal-plans/{id}/items
     * Tambah makanan ke rencana makan.
     * meal_type: breakfast | lunch | dinner | snack
     */
    public function addItem(Request $request, $id): JsonResponse
    {
        $plan = Auth::user()->mealPlans()->find($id);

        if (!$plan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Rencana makan tidak ditemukan atau Anda tidak memiliki akses',
            ], 404);
        }

        $validated = $request->validate([
            'food_id'        => 'required|integer|exists:foods,id',
            'meal_type'      => 'required|in:breakfast,lunch,dinner,snack',
            'serving_amount' => 'required|numeric|min:0.1',
            'notes'          => 'nullable|string',
        ]);

        $item = $plan->items()->create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Makanan berhasil ditambahkan ke rencana',
            'data'    => $item->load('food:id,name,category,calories,protein,fat,carbs,image_url'),
        ], 201);
    }

    /**
     * PUT /api/meal-plans/{id}/items/{itemId}
     * Update porsi, waktu makan, atau catatan dari satu item.
     */
    public function updateItem(Request $request, $id, $itemId): JsonResponse
    {
        $plan = Auth::user()->mealPlans()->find($id);

        if (!$plan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Rencana makan tidak ditemukan atau Anda tidak memiliki akses',
            ], 404);
        }

        $item = $plan->items()->find($itemId);

        if (!$item) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item makanan tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'food_id'        => 'sometimes|integer|exists:foods,id',
            'meal_type'      => 'sometimes|in:breakfast,lunch,dinner,snack',
            'serving_amount' => 'sometimes|numeric|min:0.1',
            'notes'          => 'sometimes|nullable|string',
        ]);

        $item->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Item makanan berhasil diperbarui',
            'data'    => $item->load('food:id,name,category,calories,protein,fat,carbs,image_url'),
        ]);
    }

    /**
     * DELETE /api/meal-plans/{id}/items/{itemId}
     * Hapus satu makanan dari rencana makan.
     */
    public function removeItem($id, $itemId): JsonResponse
    {
        $plan = Auth::user()->mealPlans()->find($id);

        if (!$plan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Rencana makan tidak ditemukan atau Anda tidak memiliki akses',
            ], 404);
        }

        $item = $plan->items()->find($itemId);

        if (!$item) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Item makanan tidak ditemukan',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Item makanan berhasil dihapus',
        ]);
    }
}
