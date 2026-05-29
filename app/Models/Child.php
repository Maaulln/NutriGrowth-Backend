<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'birth_date',
        'image_url',
        'weight_kg',
        'height_cm',
        'muac_cm',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'weight_kg' => 'float',
        'height_cm' => 'float',
        'muac_cm' => 'float',
    ];

    /**
     * Get the parent (user) that owns the child.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the growth records for the child.
     */
    public function growthRecords()
    {
        return $this->hasMany(ChildGrowthRecord::class);
    }
}
