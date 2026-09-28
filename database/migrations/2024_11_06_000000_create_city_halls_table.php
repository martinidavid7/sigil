<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('city_halls', function (Blueprint $table) {
            $table->id();
            $table->string('name', 140);
            $table->string('mayor', 80)->nullable();
            $table->string('cnpj', 18)->nullable();
            $table->string('state_registration', 30)->nullable();
            $table->string('address', 120)->nullable();
            $table->string('number', 10)->nullable();
            $table->string('neighborhood', 80)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('zip_code', 9)->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('city_halls');
    }
};
