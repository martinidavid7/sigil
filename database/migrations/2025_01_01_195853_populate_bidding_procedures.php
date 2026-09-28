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
        DB::table('bidding_procedures')->insert([
            [

                'mode' => 'Dispensa',
                'deadline' => '-',
                'purchase_services_minimum_value' => 0,
                'purchase_services_maximum_value' => 17600.00,
                'construction_engineering_minimum_value' => 0,
                'construction_engineering_maximum_value' => 33000.00,
                'enabled' => 1,
                'steps' => json_encode([
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mode' => 'Convite',
                'deadline' => '5 dias uteis',
                'purchase_services_minimum_value' => 17600.01,
                'purchase_services_maximum_value' => 176000.00,
                'construction_engineering_minimum_value' => 33000.01,
                'construction_engineering_maximum_value' => 3300000.00,
                'enabled' => 1,
                'steps' => json_encode([
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mode' => 'Tomada de Preços',
                'deadline' => '15 dias Corridos',
                'purchase_services_minimum_value' => 176000.01,
                'purchase_services_maximum_value' =>  1400000.00,
                'construction_engineering_minimum_value' => 330000.01,
                'construction_engineering_maximum_value' => 33000000.00,
                'enabled' => 1,
                'steps' => json_encode([
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mode' => 'Concorrência',
                'deadline' => '30 dias Corridos',
                'purchase_services_minimum_value' => 1400000.01,
                'purchase_services_maximum_value' =>  null,
                'construction_engineering_minimum_value' => 33000000.01,
                'construction_engineering_maximum_value' => null,
                'enabled' => 1,
                'steps' => json_encode([
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mode' => 'Pregão Presencial',
                'deadline' => '8 dias Uteis',
                'purchase_services_minimum_value' =>  null,
                'purchase_services_maximum_value' =>  null,
                'construction_engineering_minimum_value' => null,
                'construction_engineering_maximum_value' => null,
                'enabled' => 1,
                'steps' => json_encode([
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'mode' => 'Pregão Eletronico',
                'deadline' => '8 dias Uteis',
                'purchase_services_minimum_value' =>  null,
                'purchase_services_maximum_value' =>  null,
                'construction_engineering_minimum_value' => null,
                'construction_engineering_maximum_value' => null,
                'enabled' => 1,
                'steps' => json_encode([
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::table('bidding_procedures')->whereIn('mode', ['Dispensa', 'Convite', 'Tomada de Preços', 'Concorrência', 'Pregão Presencial',  'Pregão Eletronico'])->delete();
    }
};
