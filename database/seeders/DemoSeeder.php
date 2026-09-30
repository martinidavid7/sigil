<?php

namespace Database\Seeders;

use App\Enums\BiddingType;
use App\Models\Bidding;
use App\Models\BiddingMode;
use App\Models\CityHall;
use App\Models\Professional;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Dados fictícios para explorar o sistema: usuário de demonstração,
     * uma prefeitura configurada, secretarias com seus profissionais e
     * algumas licitações em etapas diferentes.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'demo@sigil.test'],
            ['name' => 'Usuário Demonstração', 'password' => 'password'],
        );

        if (! CityHall::isConfigured()) {
            CityHall::factory()->create([
                'name' => 'Prefeitura Municipal de Vila Esperança',
                'city' => 'Vila Esperança',
            ]);
        }

        if (Secretary::doesntExist()) {
            Secretary::factory(6)->create();
        }

        if (Professional::doesntExist()) {
            Secretary::all()->each(fn (Secretary $secretary) => Professional::factory(2)->for($secretary)->create());
        }

        if (Bidding::doesntExist()) {
            $this->seedBiddings();
        }
    }

    private function seedBiddings(): void
    {
        $samples = [
            ['Aquisição de gêneros alimentícios para a merenda escolar', 'Pregão Eletrônico', BiddingType::PurchaseServices, 480000, 11],
            ['Reforma da Unidade Básica de Saúde do bairro Centro', 'Tomada de Preços', BiddingType::ConstructionEngineering, 950000, 4],
            ['Contratação de empresa para manutenção da frota municipal', 'Convite', BiddingType::PurchaseServices, 150000, 1],
        ];

        $modes = BiddingMode::pluck('id', 'name');
        $professionals = Professional::all();
        $responsible = function () use ($professionals) {
            $professional = $professionals->random();

            return ['secretary_id' => $professional->secretary_id, 'professional_id' => $professional->id];
        };

        foreach ($samples as $index => [$subject, $mode, $type, $value, $completedStages]) {
            $bidding = Bidding::create([
                'number' => $index + 1,
                'year' => today()->year,
                'subject' => $subject,
                'bidding_mode_id' => $modes[$mode],
                'type' => $type,
                'estimated_value' => $value,
            ]);

            $date = today()->subDays(3 * $completedStages + 2);
            $bidding->begin([...$responsible(), 'started_at' => $date->toDateString()]);

            for ($stage = 1; $stage <= $completedStages; $stage++) {
                $bidding->completeCurrentStage(
                    ['completed_at' => $date->addDays(3)->toDateString(), 'page_number' => (string) ($stage * 4), 'notes' => null],
                    $responsible(),
                );
            }
        }
    }
}
