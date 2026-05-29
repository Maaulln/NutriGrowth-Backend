<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.ai.url', 'http://localhost:8000'), '/');
    }

    /**
     * Kirim data anak + food candidates ke AI server, kembalikan hasil analisis.
     *
     * @param  array $childData  Input profil anak (age, gender, weight, height, dll)
     * @param  array $foods      Daftar makanan dari DB (hasil Food::get()->toArray())
     * @return array             Response JSON dari AI server
     *
     * @throws \RuntimeException jika AI server tidak bisa dihubungi atau error
     */
    public function analyze(array $childData, array $foods): array
    {
        $payload = array_merge($childData, [
            'food_candidates' => $this->mapFoodsToAiFormat($foods),
        ]);

        try {
            $response = Http::timeout(30)->post("{$this->baseUrl}/analyze", $payload);
        } catch (\Exception $e) {
            Log::error('AiService: tidak dapat terhubung ke AI server', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Tidak dapat terhubung ke AI server. Pastikan server aktif.');
        }

        if ($response->failed()) {
            Log::error('AiService: AI server error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new \RuntimeException("AI server merespons dengan error {$response->status()}.");
        }

        return $response->json();
    }

    /**
     * Petakan kolom Food model ke format FoodCandidate yang diharapkan AI.
     */
    private function mapFoodsToAiFormat(array $foods): array
    {
        return array_map(function (array $food) {
            return [
                'id'                => (string) ($food['id'] ?? ''),
                'name'              => $food['name'],
                'category'          => $food['category'],
                'calories_per_100g' => (float) ($food['calories'] ?? 0),
                'protein_g'         => (float) ($food['protein'] ?? 0),
                'carbo_g'           => (float) ($food['carbs'] ?? 0),
                'fat_g'             => (float) ($food['fat'] ?? 0),
                'fiber_g'           => (float) ($food['fiber_g'] ?? 0),
                'serving_size_g'    => (float) ($food['serving_size_g'] ?? 100),
                'serving_size_desc' => $food['serving_size'] ?? '1 porsi',
                'price_min'         => (int) ($food['price_min'] ?: $food['price_per_serving'] ?? 0),
                'price_max'         => (int) ($food['price_max'] ?: $food['price_per_serving'] ?? 0),
                'allergens'         => $food['allergens'] ?? '',
                'age_min_months'    => (int) ($food['age_min_months'] ?? 0),
                'texture'           => $food['texture'] ?? 'lembut',
            ];
        }, $foods);
    }
}
