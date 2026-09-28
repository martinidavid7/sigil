<?php

namespace Database\Seeders;

use App\Models\BiddingStep;
use Illuminate\Database\Seeder;

class BiddingStepSeeder extends Seeder
{
    /**
     * Etapas padrão do processo licitatório, na ordem em que acontecem.
     */
    public const STEPS = [
        'Protocolo',
        'Ofício',
        'Requisição',
        'Reserva de Dotação',
        'Orçamentos',
        'Análise do Jurídico',
        'Autorização do Prefeito',
        'Minuta do Edital',
        'Parecer Jurídico do Edital',
        'Numeração do Processo',
        'Realização do Certame',
        'Prazo para Recurso',
        'Homologação e Adjudicação',
    ];

    public function run(): void
    {
        foreach (self::STEPS as $index => $name) {
            BiddingStep::updateOrCreate(
                ['name' => $name],
                ['position' => $index + 1, 'enabled' => true],
            );
        }
    }
}
