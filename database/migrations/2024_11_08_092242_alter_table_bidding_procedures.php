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
            $table->string('deadline', 50)->after('mode')->nullable();
            $table->renameColumn('minimum_value', 'purchase_services_minimum_value')->nullable();
            $table->renameColumn('maximum_value', 'purchase_services_maximum_value')->nullable();
            $table->double('construction_engineering_minimum_value', 50)->after('purchase_services_maximum_value')->nullable();
            $table->double('construction_engineering_maximum_value', 50)->after('construction_engineering_minimum_value')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
