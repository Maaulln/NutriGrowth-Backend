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
        Schema::create('recommendation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recommendation_result_id')
                ->constrained('recommendation_results')
                ->cascadeOnDelete();
            $table->foreignId('food_id')->nullable()->constrained('foods')->restrictOnDelete();
            $table->string('food_name');
            $table->string('category')->nullable();
            $table->string('serving_size')->nullable();
            $table->integer('estimated_price')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['recommendation_result_id', 'food_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_items');
    }
};
