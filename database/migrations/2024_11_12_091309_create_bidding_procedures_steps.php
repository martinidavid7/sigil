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
        Schema::create('bidding_procedures_steps', function (Blueprint $table) {
            $table->id();
            $table->string('step_name', 80);
           // $table->string('document_number', 50)->nullable();
            //$table->string('situation', 50)->nullable();
            //$table->string('page', 50)->nullable();
            //$table->string('responsible', 150)->nullable();
            //$table->date('start_date', 10)->nullable();
            //$table->date('deadline', 10)->nullable();
            //$table->date('end_date', 10)->nullable();
            $table->float('order', precision: 3);
            $table->boolean('enabled')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidding_procedures_steps');
    }
};
