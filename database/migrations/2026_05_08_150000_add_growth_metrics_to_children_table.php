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
        Schema::table('children', function (Blueprint $table) {
            $table->decimal('weight_kg', 5, 2)->nullable()->after('image_url');
            $table->decimal('height_cm', 5, 2)->nullable()->after('weight_kg');
            $table->decimal('muac_cm', 5, 2)->nullable()->after('height_cm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn(['weight_kg', 'height_cm', 'muac_cm']);
        });
    }
};
