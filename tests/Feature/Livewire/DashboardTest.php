<?php

namespace Tests\Feature\Livewire;

use App\Models\CityHall;
use App\Models\User;
use Database\Seeders\BiddingModeSeeder;
use Database\Seeders\BiddingStepSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_asks_to_configure_the_city_hall_first(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Comece pela prefeitura')
            ->assertDontSee('Secretarias');
    }

    public function test_shows_modes_and_process_flow_from_seeders(): void
    {
        $this->seed([BiddingStepSeeder::class, BiddingModeSeeder::class]);
        CityHall::factory()->create(['name' => 'Prefeitura Municipal de Teste']);

        $this->actingAs(User::factory()->create())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Prefeitura Municipal de Teste')
            ->assertSee('Pregão Eletrônico')
            ->assertSee('R$ 1.430.000,00')
            ->assertSee('Homologação e Adjudicação');
    }
}
