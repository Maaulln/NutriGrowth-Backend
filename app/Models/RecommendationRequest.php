<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecommendationRequest extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'child_id',
        'payload',
        'status',
        'error_message',
    ];

    /**
     * Casting tipe data.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Ambil user pemilik request rekomendasi.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil anak terkait request rekomendasi.
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Ambil hasil rekomendasi dari request ini.
     */
    public function results()
    {
        return $this->hasMany(RecommendationResult::class);
    }
}
