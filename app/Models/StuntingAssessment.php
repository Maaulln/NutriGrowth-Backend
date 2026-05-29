<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StuntingAssessment extends Model
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
        'risk_level',
        'risk_score',
        'summary',
        'analysis',
        'recommendations',
        'warning_flags',
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
        'analysis' => 'array',
        'recommendations' => 'array',
        'warning_flags' => 'array',
    ];

    /**
     * Ambil user pemilik assessment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil anak terkait assessment.
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
