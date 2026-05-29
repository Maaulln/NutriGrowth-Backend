<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationResult extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'recommendation_request_id',
        'summary',
        'budget_min',
        'budget_max',
        'confidence_note',
        'raw_response',
    ];

    /**
     * Casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'raw_response' => 'array',
    ];

    /**
     * Ambil request yang menghasilkan rekomendasi ini.
     */
    public function request()
    {
        return $this->belongsTo(RecommendationRequest::class, 'recommendation_request_id');
    }

    /**
     * Ambil item makanan yang direkomendasikan.
     */
    public function items()
    {
        return $this->hasMany(RecommendationItem::class);
    }
}
