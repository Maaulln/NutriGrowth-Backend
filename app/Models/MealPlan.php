<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'child_id',
        'plan_date',
        'title',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'plan_date' => 'date',
    ];

    /**
     * Get the user that owns the meal plan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the child assigned to the meal plan.
     */
    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    /**
     * Get the meal plan items for the meal plan.
     */
    public function items()
    {
        return $this->hasMany(MealPlanItem::class);
    }
}
