<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * As etapas são as mesmas para todas as modalidades: o fluxo de uma
     * licitação segue as etapas ativas do cadastro, e não um vínculo por modalidade.
     */
    public function up(): void
    {
        Schema::dropIfExists('bidding_mode_step');
    }

    public function down(): void
    {
        Schema::create('bidding_mode_step', function (Blueprint $table) {
            $table->foreignId('bidding_mode_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bidding_step_id')->constrained()->cascadeOnDelete();
            $table->primary(['bidding_mode_id', 'bidding_step_id']);
        });
    }
};
