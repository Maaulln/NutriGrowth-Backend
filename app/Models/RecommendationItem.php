<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationItem extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recommendation_result_id',
        'food_id',
        'food_name',
        'category',
        'serving_size',
        'estimated_price',
        'reason',
    ];

    /**
     * Ambil hasil rekomendasi yang memiliki item ini.
     */
    public function result()
    {
        return $this->belongsTo(RecommendationResult::class, 'recommendation_result_id');
    }

    /**
     * Ambil data makanan yang direferensikan.
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
