<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('secretaries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('responsible_name', 80);
            $table->string('phone', 20);
            $table->string('address', 120);
            $table->string('number', 10);
            $table->string('neighborhood', 80);
            $table->string('zip_code', 9)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secretaries');
    }
};
