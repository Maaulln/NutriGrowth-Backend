<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->float('fiber_g')->default(0)->after('carbs');
            $table->float('serving_size_g')->default(100)->after('serving_size');
            $table->integer('price_min')->default(0)->after('price_per_serving');
            $table->integer('price_max')->default(0)->after('price_min');
            $table->string('allergens')->nullable()->after('image_url');
            $table->integer('age_min_months')->default(0)->after('allergens');
            // cair (0-6 bln) | lembut (6+ bln) | padat (24+ bln)
            $table->string('texture')->default('lembut')->after('age_min_months');
        });
    }

    public function down(): void
    {
        Schema::table('foods', function (Blueprint $table) {
            $table->dropColumn([
                'fiber_g', 'serving_size_g', 'price_min', 'price_max',
                'allergens', 'age_min_months', 'texture',
            ]);
        });
    }
};
