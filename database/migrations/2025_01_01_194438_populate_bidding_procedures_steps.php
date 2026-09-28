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
        DB::table('bidding_procedures_steps')->insert([
            [

                'step_name' => 'Protocolo',
                'order' => 1,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Ofício',
                'order' => 2,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Requsição',
                'order' => 3,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Reserva de Dotação',
                'order' => 4,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Orçamentos',
                'order' => 5,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Análise do Jurídico',
                'order' => 6,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Autorizacao do Prefeito',
                'order' => 7,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Minuta do Edital',
                'order' => 8,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Parecer Juridico do Edital',
                'order' => 9,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Numeração do Processo',
                'order' => 10,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Realização do Certame',
                'order' => 11,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Prazo para Recurso',
                'order' => 12,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [

                'step_name' => 'Homologação e Adjudicação',
                'order' => 13,
                'enabled' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        DB::table('bidding_procedures_steps')
            ->whereIn('step_name', [
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
            ])
            ->delete();
    }
};
