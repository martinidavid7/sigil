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
        Schema::table('bidding_procedures', function (Blueprint $table) {
            $table->string('situation')->after('construction_engineering_maximum_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bidding_procedures', function (Blueprint $table) {
            Schema::dropIfExists('situation');
        });
    }
};
