<?php

namespace App\Console\Commands;

use App\Models\Food;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchFoodImages extends Command
{
    protected $signature = 'foods:fetch-images
                            {--force : Timpa image_url yang sudah ada}
                            {--id= : Hanya update food dengan ID tertentu}';

    protected $description = 'Ambil gambar makanan dari Unsplash API dan simpan URL-nya ke database';

    private string $baseUrl = 'https://api.unsplash.com';

    // Terjemahan nama makanan Indonesia → Inggris agar hasil Unsplash lebih relevan
    private array $translations = [
        'Telur'              => 'egg food',
        'Dada Ayam'          => 'chicken breast',
        'Hati Ayam'          => 'chicken liver',
        'Daging Sapi'        => 'beef meat',
        'Tahu'               => 'tofu',
        'Tempe'              => 'tempeh',
        'Ikan Kembung'       => 'mackerel fish',
        'Ikan Teri'          => 'anchovy fish',
        'Udang'              => 'shrimp seafood',
        'Kacang Hijau'       => 'mung beans',
        'Kacang Tanah'       => 'peanut',
        'Ikan Salmon'        => 'salmon fish',
        'Nasi Putih'         => 'white rice bowl',
        'Nasi Merah'         => 'brown rice',
        'Bubur Beras'        => 'rice porridge congee',
        'Ubi Jalar'          => 'sweet potato',
        'Singkong'           => 'cassava',
        'Kentang'            => 'potato',
        'Jagung'             => 'corn',
        'Roti Tawar'         => 'bread loaf',
        'Oatmeal'            => 'oatmeal bowl',
        'Bubur Maizena'      => 'cornstarch porridge baby food',
        'Bayam'              => 'spinach',
        'Kangkung'           => 'water spinach',
        'Brokoli'            => 'broccoli',
        'Wortel'             => 'carrot',
        'Labu Kuning'        => 'pumpkin',
        'Labu Siam'          => 'chayote squash',
        'Kacang Panjang'     => 'long beans',
        'Tomat'              => 'tomato',
        'Daun Singkong'      => 'cassava leaves',
        'Pisang'             => 'banana',
        'Pepaya'             => 'papaya',
        'Mangga'             => 'mango',
        'Jeruk'              => 'orange fruit',
        'Alpukat'            => 'avocado',
        'Semangka'           => 'watermelon',
        'Melon'              => 'melon fruit',
        'Pir'                => 'pear fruit',
        'Susu Sapi'          => 'cow milk glass',
        'Yoghurt'            => 'yogurt bowl',
        'Keju'               => 'cheese',
        'Susu UHT'           => 'milk carton',
        // Varian spesifik
        'Kacang Tanah Sangrai'  => 'roasted peanut',
        'Bubur Beras'           => 'congee',
        'Kentang Rebus'         => 'boiled potato',
        'Oatmeal Matang'        => 'oatmeal bowl',
        'Labu Siam Kukus'       => 'chayote squash steamed',
        'Susu Sapi Full Cream'  => 'fresh milk glass',
        'Susu UHT Full Cream'   => 'milk carton',
    ];

    public function handle(): int
    {
        $accessKey = config('services.unsplash.access_key');

        if (!$accessKey || $accessKey === 'your_unsplash_access_key_here') {
            $this->error('UNSPLASH_ACCESS_KEY belum diset di file .env');
            return self::FAILURE;
        }

        $query = Food::query();

        if ($this->option('id')) {
            $query->where('id', (int) $this->option('id'));
        } elseif (!$this->option('force')) {
            $query->whereNull('image_url')->orWhere('image_url', '');
        }

        $foods = $query->get();

        if ($foods->isEmpty()) {
            $this->info('Semua makanan sudah memiliki gambar. Gunakan --force untuk menimpa.');
            return self::SUCCESS;
        }

        $this->info("Mengambil gambar untuk {$foods->count()} makanan dari Unsplash...");
        $bar = $this->output->createProgressBar($foods->count());
        $bar->start();

        $success = 0;
        $failed  = 0;

        foreach ($foods as $food) {
            $query = $this->translations[$food->name] ?? $food->name . ' food';

            try {
                $url = $this->fetchImageUrl($accessKey, $query);

                if ($url) {
                    $food->update(['image_url' => $url]);
                    $success++;
                } else {
                    $this->newLine();
                    $this->warn("  Tidak ada hasil untuk: {$food->name}");
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("  Gagal: {$food->name} — {$e->getMessage()}");
                Log::error('FetchFoodImages: gagal fetch', ['food' => $food->name, 'error' => $e->getMessage()]);
                $failed++;
            }

            $bar->advance();

            // Jaga rate limit Unsplash free tier (50 req/jam)
            usleep(200000); // 200ms antar request
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Selesai. Berhasil: {$success} | Gagal: {$failed}");

        return self::SUCCESS;
    }

    private function fetchImageUrl(string $accessKey, string $query): ?string
    {
        $response = Http::withHeaders([
            'Authorization' => "Client-ID {$accessKey}",
        ])->get("{$this->baseUrl}/search/photos", [
            'query'       => $query,
            'per_page'    => 1,
            'orientation' => 'landscape',
            'content_filter' => 'high',
        ]);

        if ($response->failed()) {
            throw new \RuntimeException("Unsplash API error {$response->status()}");
        }

        $results = $response->json('results', []);

        if (empty($results)) {
            return null;
        }

        // Gunakan ukuran 'small' (400px) — cukup untuk tampilan mobile, hemat bandwidth
        return $results[0]['urls']['small'] ?? $results[0]['urls']['regular'] ?? null;
    }
}
