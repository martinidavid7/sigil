<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Modalidades de licitação (Dispensa, Convite, Concorrência, ...) e as
     * faixas de valor em que cada uma se aplica.
     */
    public function up(): void
    {
        Schema::create('bidding_modes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('deadline', 50)->nullable();
            $table->decimal('purchase_services_minimum_value', 15, 2)->nullable();
            $table->decimal('purchase_services_maximum_value', 15, 2)->nullable();
            $table->decimal('construction_engineering_minimum_value', 15, 2)->nullable();
            $table->decimal('construction_engineering_maximum_value', 15, 2)->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('bidding_mode_step', function (Blueprint $table) {
            $table->foreignId('bidding_mode_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bidding_step_id')->constrained()->cascadeOnDelete();
            $table->primary(['bidding_mode_id', 'bidding_step_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidding_mode_step');
        Schema::dropIfExists('bidding_modes');
    }
};
