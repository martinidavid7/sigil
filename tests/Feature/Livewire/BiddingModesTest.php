<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BiddingModes\Index;
use App\Models\BiddingMode;
use App\Models\BiddingStep;
use App\Models\CityHall;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BiddingModesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CityHall::factory()->create();
        $this->actingAs(User::factory()->create());
    }

    public function test_new_modes_start_with_every_active_step_selected(): void
    {
        $steps = BiddingStep::factory(3)->create();
        BiddingStep::factory()->disabled()->create();

        Livewire::test(Index::class)
            ->call('create')
            ->assertSet('form.steps', $steps->pluck('id')->map(fn ($id) => (string) $id)->all());
    }

    public function test_creates_a_mode_converting_brazilian_currency(): void
    {
        [$protocol, $quotes] = BiddingStep::factory(2)->create();

        Livewire::test(Index::class)
            ->call('create')
            ->set('form.name', 'Convite')
            ->set('form.deadline', '5 dias úteis')
            ->set('form.purchase_services_minimum_value', '17.600,01')
            ->set('form.purchase_services_maximum_value', '176.000,00')
            ->set('form.construction_engineering_minimum_value', '33.000,01')
            ->set('form.construction_engineering_maximum_value', '')
            ->set('form.steps', [(string) $quotes->id])
            ->call('save')
            ->assertHasNoErrors();

        $mode = BiddingMode::where('name', 'Convite')->firstOrFail();

        $this->assertSame('17600.01', $mode->purchase_services_minimum_value);
        $this->assertSame('176000.00', $mode->purchase_services_maximum_value);
        $this->assertNull($mode->construction_engineering_maximum_value);
        $this->assertSame([$quotes->id], $mode->steps->pluck('id')->all());
    }

    public function test_maximum_value_cannot_be_below_minimum(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->set('form.name', 'Faixa inválida')
            ->set('form.purchase_services_minimum_value', '176.000,00')
            ->set('form.purchase_services_maximum_value', '17.600,00')
            ->set('form.construction_engineering_minimum_value', '12,3,4')
            ->call('save')
            ->assertHasErrors([
                'form.purchase_services_maximum_value',
                'form.construction_engineering_minimum_value' => 'regex',
            ]);

        $this->assertDatabaseMissing('bidding_modes', ['name' => 'Faixa inválida']);
    }

    public function test_edit_loads_formatted_values_and_steps(): void
    {
        $step = BiddingStep::factory()->create();
        $mode = BiddingMode::factory()->create(['purchase_services_maximum_value' => 1430000]);
        $mode->steps()->attach($step);

        Livewire::test(Index::class)
            ->call('edit', $mode->id)
            ->assertSet('form.purchase_services_maximum_value', '1.430.000,00')
            ->assertSet('form.steps', [(string) $step->id])
            ->set('form.steps', [])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertCount(0, $mode->fresh()->steps);
    }

    public function test_toggles_and_deletes_a_mode(): void
    {
        $mode = BiddingMode::factory()->create();

        Livewire::test(Index::class)->call('toggle', $mode->id);
        $this->assertFalse($mode->fresh()->enabled);

        Livewire::test(Index::class)->call('delete', $mode->id);
        $this->assertModelMissing($mode);
    }
}
