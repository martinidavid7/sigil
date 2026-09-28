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
        Schema::create('public_bidding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidding_procedure_id')
                ->constrained()  // Associa automaticamente à tabela 'bidding_procedures' e à coluna 'id'
                ->onDelete('cascade'); // Quando o procedimento de licitação for deletado, exclui todos os registros associados
           
           
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_bidding');
    }
};
