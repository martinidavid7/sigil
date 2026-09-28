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
        Schema::create('citty_hall', function (Blueprint $table) {
            $table->id();
            $table->string('city_hall', 140);
            $table->string('mayor', 50)->nullable();
            $table->string('address', 80)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('neighborhood', 80)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('cnpj', 18)->nullable();
            $table->string('inscricao_estadual', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citty_hall');
    }
};
