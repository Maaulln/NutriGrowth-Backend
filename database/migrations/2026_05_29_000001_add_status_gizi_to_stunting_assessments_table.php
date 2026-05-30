<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stunting_assessments', function (Blueprint $table) {
            $table->string('status_gizi')->nullable()->after('risk_score');
        });
    }

    public function down(): void
    {
        Schema::table('stunting_assessments', function (Blueprint $table) {
            $table->dropColumn('status_gizi');
        });
    }
};
