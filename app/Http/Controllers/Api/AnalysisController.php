<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\RecommendationItem;
use App\Models\RecommendationRequest;
use App\Models\RecommendationResult;
use App\Models\StuntingAssessment;
use App\Services\AiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalysisController extends Controller
{
    public function __construct(private AiService $aiService) {}

    /**
     * POST /api/analyze
     *
     * Alur:
     *  1. Ambil makanan dari DB (filter usia & budget)
     *  2. Kirim ke AI server beserta food_candidates
     *  3. Simpan hasil ke StuntingAssessment + RecommendationRequest/Result/Items
     *  4. Kembalikan response AI ke client
     */
    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'child_id'          => 'required|integer',
            'child_age_months'  => 'required|integer|min:0|max:216',
            'gender'            => 'required|in:male,female',
            'weight_kg'         => 'required|numeric|min:0',
            'height_cm'         => 'required|numeric|min:0',
            'muac_cm'           => 'nullable|numeric|min:0',
            'allergies'         => 'nullable|array',
            'allergies.*'       => 'string',
            'budget_min'        => 'nullable|integer|min:0',
            'budget_max'        => 'nullable|integer|min:0',
            'nutrition_history' => 'nullable|array',
        ]);

        $userId    = $request->user()->id;
        $childId   = $validated['child_id'];
        $budgetMax = $validated['budget_max'] ?? 50000;
        $budgetMin = $validated['budget_min'] ?? 0;

        // Ambil makanan dari DB sesuai usia anak dan budget
        $foods = Food::where('age_min_months', '<=', $validated['child_age_months'])
            ->where(function ($q) use ($budgetMax) {
                // Sertakan makanan yang price_min <= budget, atau jika price_min belum diset (0)
                $q->where('price_min', '<=', $budgetMax)
                  ->orWhere('price_min', 0);
            })
            ->get()
            ->toArray();

        // Simpan RecommendationRequest (input ke AI)
        $aiPayload = array_merge($validated, [
            'user_id'    => $userId,
            'budget_min' => $budgetMin,
            'budget_max' => $budgetMax,
            'allergies'  => $validated['allergies'] ?? [],
        ]);

        $recRequest = RecommendationRequest::create([
            'user_id'  => $userId,
            'child_id' => $childId,
            'payload'  => $aiPayload,
            'status'   => 'pending',
        ]);

        try {
            $aiResult = $this->aiService->analyze($aiPayload, $foods);
        } catch (\RuntimeException $e) {
            $recRequest->update(['status' => 'error', 'error_message' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 502);
        }

        $recRequest->update(['status' => 'success']);

        // Simpan stunting assessment
        StuntingAssessment::create([
            'user_id'         => $userId,
            'child_id'        => $childId,
            'payload'         => $aiPayload,
            'risk_level'      => $aiResult['risk_level'] ?? 'low',
            'risk_score'      => $aiResult['risk_score'] ?? 0,
            'summary'         => $aiResult['summary'] ?? '',
            'analysis'        => $aiResult['analysis'] ?? [],
            'recommendations' => $aiResult['treatment_recommendations'] ?? [],
            'warning_flags'   => $aiResult['warning_flags'] ?? [],
            'status'          => 'success',
        ]);

        // Simpan recommendation result + items
        $recResult = RecommendationResult::create([
            'recommendation_request_id' => $recRequest->id,
            'summary'                   => $aiResult['food_summary'] ?? '',
            'budget_min'                => $budgetMin,
            'budget_max'                => $budgetMax,
            'confidence_note'           => '',
            'raw_response'              => $aiResult,
        ]);

        foreach ($aiResult['food_items'] ?? [] as $item) {
            RecommendationItem::create([
                'recommendation_result_id' => $recResult->id,
                'food_name'                => $item['food_name'],
                'category'                 => $item['category'],
                'serving_size'             => $item['serving_size'],
                'estimated_price'          => $item['estimated_price'],
                'reason'                   => $item['reason'],
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Analisis gizi berhasil',
            'data'    => $aiResult,
        ]);
    }
}
