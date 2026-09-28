<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Etapas que compõem o processo de uma licitação (Protocolo, Ofício, ...).
     */
    public function up(): void
    {
        Schema::create('bidding_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidding_steps');
    }
};
