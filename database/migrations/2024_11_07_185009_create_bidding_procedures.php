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
        Schema::create('bidding_procedures', function (Blueprint $table) {
            $table->id();
            $table->string('modalidade', 120);
            $table->double('valor_minimo', 50)->nullable();
            $table->double('valor_maximo', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidding_procedures');
    }
};
