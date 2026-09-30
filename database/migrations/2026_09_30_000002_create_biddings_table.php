<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Licitações e o histórico das etapas pelas quais cada uma passou.
     */
    public function up(): void
    {
        Schema::create('biddings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number');
            $table->unsignedSmallInteger('year');
            $table->text('subject');
            $table->foreignId('bidding_mode_id')->constrained()->restrictOnDelete();
            $table->string('type', 30);
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->date('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['number', 'year']);
        });

        // Só as etapas já iniciadas ficam gravadas; as seguintes são lidas do
        // cadastro de etapas no momento em que a anterior é concluída.
        Schema::create('bidding_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bidding_step_id')->constrained()->restrictOnDelete();
            $table->foreignId('secretary_id')->constrained()->restrictOnDelete();
            $table->foreignId('professional_id')->constrained()->restrictOnDelete();
            $table->date('started_at');
            $table->date('completed_at')->nullable();
            $table->string('page_number', 20)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['bidding_id', 'bidding_step_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidding_stages');
        Schema::dropIfExists('biddings');
    }
};
