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
        //
        Schema::dropIfExists('table_modalidades_licitacoes');
        Schema::dropIfExists('secretary_name');
        Schema::dropIfExists('modalidades_licitacoes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
