<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meal_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_plan_id')->constrained('meal_plans')->cascadeOnDelete();
            $table->foreignId('food_id')->constrained('foods')->restrictOnDelete();
            $table->string('meal_type');
            $table->string('serving_amount');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['meal_plan_id', 'meal_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_plan_items');
    }
};
