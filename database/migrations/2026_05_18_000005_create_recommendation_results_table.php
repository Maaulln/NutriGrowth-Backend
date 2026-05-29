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
        Schema::create('recommendation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recommendation_request_id')
                ->constrained('recommendation_requests')
                ->cascadeOnDelete();
            $table->text('summary');
            $table->integer('budget_min')->nullable();
            $table->integer('budget_max')->nullable();
            $table->text('confidence_note')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();

            $table->index(['recommendation_request_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendation_results');
    }
};
