<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildGrowthRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'child_id',
        'recorded_at',
        'weight_kg',
        'height_cm',
        'muac_cm',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'recorded_at' => 'date',
        'weight_kg' => 'float',
        'height_cm' => 'float',
        'muac_cm' => 'float',
    ];

    /**
     * Get the child that owns the growth record.
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
