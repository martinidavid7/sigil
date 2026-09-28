<?php

namespace Database\Seeders;

use App\Models\CityHall;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Dados fictícios para explorar o sistema: usuário de demonstração,
     * uma prefeitura configurada e algumas secretarias.
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
    }
}
