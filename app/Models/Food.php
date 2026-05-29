<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'foods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'calories',
        'protein',
        'fat',
        'carbs',
        'fiber_g',
        'price_per_serving',
        'price_min',
        'price_max',
        'serving_size',
        'serving_size_g',
        'description',
        'image_url',
        'allergens',
        'age_min_months',
        'texture',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'calories'        => 'float',
        'protein'         => 'float',
        'fat'             => 'float',
        'carbs'           => 'float',
        'fiber_g'         => 'float',
        'serving_size_g'  => 'float',
        'price_per_serving' => 'integer',
        'price_min'       => 'integer',
        'price_max'       => 'integer',
        'age_min_months'  => 'integer',
    ];
}
