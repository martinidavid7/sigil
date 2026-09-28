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
            $table->renameColumn('modalidade', 'mode');
            $table->renameColumn('valor_minimo', 'minimum_value');
            $table->renameColumn('valor_maximo', 'maximum_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bidding_procedures', function (Blueprint $table) {
            $table->renameColumn('modalidade', 'mode');
            $table->renameColumn('valor_minimo', 'minimum_value');
            $table->renameColumn('valor_maximo', 'maximum_value');
        });
    }
};
