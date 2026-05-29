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
        Schema::create('stunting_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('child_id')->nullable()->constrained('children')->nullOnDelete();
            $table->json('payload');
            $table->string('risk_level')->nullable();
            $table->integer('risk_score')->nullable();
            $table->text('summary')->nullable();
            $table->json('analysis')->nullable();
            $table->json('recommendations')->nullable();
            $table->json('warning_flags')->nullable();
            $table->string('status')->default('success');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stunting_assessments');
    }
};
