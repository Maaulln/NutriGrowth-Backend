<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah profil gizi anak: alergi, ASI eksklusif, suplemen, frekuensi sakit.
     */
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->json('allergies')->nullable()->after('muac_cm');
            $table->boolean('exclusive_breastfeeding')->default(true)->after('allergies');
            $table->string('supplement_intake')->default('none')->after('exclusive_breastfeeding');
            $table->string('illness_frequency')->default('low')->after('supplement_intake');
        });
    }

    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn([
                'allergies',
                'exclusive_breastfeeding',
                'supplement_intake',
                'illness_frequency',
            ]);
        });
    }
};
