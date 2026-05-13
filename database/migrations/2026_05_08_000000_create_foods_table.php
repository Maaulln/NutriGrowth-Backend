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
        Schema::create('foods', function (Blueprint $荒) {
            $荒->id();
            $荒->string('name');
            $荒->string('category'); // protein, carbo, vegetable, fruit, dairy
            $荒->float('calories')->default(0);
            $荒->float('protein')->default(0);
            $荒->float('fat')->default(0);
            $荒->float('carbs')->default(0);
            $荒->integer('price_per_serving')->default(0);
            $荒->string('serving_size')->default('100g');
            $荒->text('description')->nullable();
            $荒->string('image_url')->nullable();
            $荒->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
