<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlanItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meal_plan_id',
        'food_id',
        'meal_type',
        'serving_amount',
        'notes',
    ];

    /**
     * Get the meal plan that owns the item.
     */
    public function mealPlan()
    {
        return $this->belongsTo(MealPlan::class);
    }

    /**
     * Get the food referenced by the item.
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
