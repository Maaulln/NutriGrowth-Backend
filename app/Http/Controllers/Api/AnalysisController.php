<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\ChildGrowthRecord;
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
            'status_gizi'     => $aiResult['status_gizi'] ?? null,
            'risk_level'      => $aiResult['risk_level'] ?? 'low',
            'risk_score'      => $aiResult['risk_score'] ?? 0,
            'summary'         => $aiResult['summary'] ?? '',
            'analysis'        => $aiResult['analysis'] ?? [],
            'recommendations' => $aiResult['treatment_recommendations'] ?? [],
            'warning_flags'   => $aiResult['warning_flags'] ?? [],
            'status'          => 'success',
        ]);

        // Auto-simpan growth record — updateOrCreate agar tidak duplikat jika 2x analisis di hari sama
        ChildGrowthRecord::updateOrCreate(
            ['child_id' => $childId, 'recorded_at' => now()->toDateString()],
            [
                'weight_kg' => $validated['weight_kg'],
                'height_cm' => $validated['height_cm'],
                'muac_cm'   => $validated['muac_cm'] ?? null,
                'notes'     => 'Dicatat otomatis dari analisis gizi',
            ]
        );

        // Simpan recommendation result + items
        $recResult = RecommendationResult::create([
            'recommendation_request_id' => $recRequest->id,
            'summary'                   => $aiResult['food_summary'] ?? '',
            'budget_min'                => $budgetMin,
            'budget_max'                => $budgetMax,
            'confidence_note'           => '',
            'raw_response'              => $aiResult,
        ]);

        // Enrich food_items dengan image_url dari DB berdasarkan nama makanan
        $foodNames  = collect($aiResult['food_items'] ?? [])->pluck('food_name');
        $foodImages = Food::whereIn('name', $foodNames)->pluck('image_url', 'name');

        $aiResult['food_items'] = array_map(function (array $item) use ($foodImages) {
            $item['image_url'] = $foodImages[$item['food_name']] ?? null;
            return $item;
        }, $aiResult['food_items'] ?? []);

        foreach ($aiResult['food_items'] as $item) {
            RecommendationItem::create([
                'recommendation_result_id' => $recResult->id,
                'food_name'                => $item['food_name'],
                'category'                 => $item['category'],
                'serving_size'             => $item['serving_size'],
                'estimated_price'          => $item['estimated_price'],
                'reason'                   => $item['reason'],
            ]);
        }

        // Buat notifikasi berdasarkan hasil analisis
        $this->createAnalysisNotification($userId, $aiResult['status_gizi'] ?? 'normal');

        return response()->json([
            'status'  => 'success',
            'message' => 'Analisis gizi berhasil',
            'data'    => $aiResult,
        ]);
    }

    private function createAnalysisNotification(int $userId, string $statusGizi): void
    {
        $statusMap = [
            'severely stunted' => 'Sangat Pendek (Severely Stunted)',
            'stunted'          => 'Pendek (Stunted)',
            'normal'           => 'Normal',
            'tinggi'           => 'Tinggi',
        ];

        $statusLabel = $statusMap[strtolower($statusGizi)] ?? $statusGizi;

        if (in_array(strtolower($statusGizi), ['stunted', 'severely stunted'])) {
            AppNotification::create([
                'user_id' => $userId,
                'type'    => 'stunting_alert',
                'title'   => 'Perhatian: Risiko Stunting Terdeteksi',
                'message' => "Hasil analisis menunjukkan status gizi anak: {$statusLabel}. Segera lihat rekomendasi makanan dan konsultasikan dengan tenaga kesehatan.",
            ]);
        } else {
            AppNotification::create([
                'user_id' => $userId,
                'type'    => 'analysis_done',
                'title'   => 'Hasil Analisis Gizi Tersedia',
                'message' => "Analisis selesai. Status gizi anak: {$statusLabel}. Lihat rekomendasi makanan untuk mendukung pertumbuhan optimal.",
            ]);
        }
    }

    /**
     * GET /api/analyses?child_id={id}
     * Riwayat semua assessment milik user yang login (opsional filter per anak).
     */
    public function index(Request $request): JsonResponse
    {
        $query = StuntingAssessment::where('user_id', $request->user()->id)
            ->latest()
            ->limit(20);

        if ($request->has('child_id')) {
            $query->where('child_id', (int) $request->query('child_id'));
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->get(['id', 'child_id', 'status_gizi', 'risk_level', 'risk_score', 'summary', 'created_at']),
        ]);
    }

    /**
     * GET /api/children/{childId}/assessments
     * Riwayat assessment untuk satu anak milik user yang login.
     */
    public function childAssessments(Request $request, int $childId): JsonResponse
    {
        $assessments = StuntingAssessment::where('user_id', $request->user()->id)
            ->where('child_id', $childId)
            ->latest()
            ->limit(10)
            ->get(['id', 'child_id', 'status_gizi', 'risk_level', 'risk_score', 'summary', 'recommendations', 'warning_flags', 'created_at']);

        return response()->json([
            'status' => 'success',
            'data'   => $assessments,
        ]);
    }
}
