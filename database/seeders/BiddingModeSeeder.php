<?php

namespace Database\Seeders;

use App\Models\BiddingMode;
use Illuminate\Database\Seeder;

class BiddingModeSeeder extends Seeder
{
    /**
     * Modalidades da Lei 8.666/93, com os limites atualizados pelo
     * Decreto 9.412/2018. Os pregões (Lei 10.520/02) não têm faixa de valor.
     */
    public function run(): void
    {
        $modes = [
            [
                'name' => 'Dispensa',
                'deadline' => null,
                'purchase_services_minimum_value' => 0,
                'purchase_services_maximum_value' => 17600.00,
                'construction_engineering_minimum_value' => 0,
                'construction_engineering_maximum_value' => 33000.00,
            ],
            [
                'name' => 'Convite',
                'deadline' => '5 dias úteis',
                'purchase_services_minimum_value' => 17600.01,
                'purchase_services_maximum_value' => 176000.00,
                'construction_engineering_minimum_value' => 33000.01,
                'construction_engineering_maximum_value' => 330000.00,
            ],
            [
                'name' => 'Tomada de Preços',
                'deadline' => '15 dias corridos',
                'purchase_services_minimum_value' => 176000.01,
                'purchase_services_maximum_value' => 1430000.00,
                'construction_engineering_minimum_value' => 330000.01,
                'construction_engineering_maximum_value' => 3300000.00,
            ],
            [
                'name' => 'Concorrência',
                'deadline' => '30 dias corridos',
                'purchase_services_minimum_value' => 1430000.01,
                'purchase_services_maximum_value' => null,
                'construction_engineering_minimum_value' => 3300000.01,
                'construction_engineering_maximum_value' => null,
            ],
            [
                'name' => 'Pregão Presencial',
                'deadline' => '8 dias úteis',
                'purchase_services_minimum_value' => null,
                'purchase_services_maximum_value' => null,
                'construction_engineering_minimum_value' => null,
                'construction_engineering_maximum_value' => null,
            ],
            [
                'name' => 'Pregão Eletrônico',
                'deadline' => '8 dias úteis',
                'purchase_services_minimum_value' => null,
                'purchase_services_maximum_value' => null,
                'construction_engineering_minimum_value' => null,
                'construction_engineering_maximum_value' => null,
            ],
        ];

        foreach ($modes as $data) {
            BiddingMode::updateOrCreate(
                ['name' => $data['name']],
                $data + ['enabled' => true],
            );
        }
    }
}
