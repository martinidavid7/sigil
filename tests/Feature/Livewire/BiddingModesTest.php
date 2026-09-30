<?php

namespace Tests\Feature\Livewire;

use App\Enums\BiddingType;
use App\Livewire\BiddingModes\Index;
use App\Models\Bidding;
use App\Models\BiddingMode;
use App\Models\CityHall;
use App\Models\User;
use Database\Seeders\BiddingModeSeeder;
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

    public function test_creates_a_mode_converting_brazilian_currency(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->set('form.name', 'Convite')
            ->set('form.deadline', '5 dias úteis')
            ->set('form.purchase_services_minimum_value', '17.600,01')
            ->set('form.purchase_services_maximum_value', '176.000,00')
            ->set('form.construction_engineering_minimum_value', '33.000,01')
            ->set('form.construction_engineering_maximum_value', '')
            ->call('save')
            ->assertHasNoErrors();

        $mode = BiddingMode::where('name', 'Convite')->firstOrFail();

        $this->assertSame('17600.01', $mode->purchase_services_minimum_value);
        $this->assertSame('176000.00', $mode->purchase_services_maximum_value);
        $this->assertNull($mode->construction_engineering_maximum_value);
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

    public function test_edit_loads_formatted_values(): void
    {
        $mode = BiddingMode::factory()->create(['purchase_services_maximum_value' => 1430000]);

        Livewire::test(Index::class)
            ->call('edit', $mode->id)
            ->assertSet('form.purchase_services_maximum_value', '1.430.000,00')
            ->set('form.purchase_services_maximum_value', '1.500.000,00')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('1500000.00', $mode->fresh()->purchase_services_maximum_value);
    }

    public function test_toggles_and_deletes_a_mode(): void
    {
        $mode = BiddingMode::factory()->create();

        Livewire::test(Index::class)->call('toggle', $mode->id);
        $this->assertFalse($mode->fresh()->enabled);

        Livewire::test(Index::class)->call('delete', $mode->id);
        $this->assertModelMissing($mode);
    }

    public function test_a_mode_used_by_biddings_cannot_be_deleted(): void
    {
        $bidding = Bidding::factory()->create();

        Livewire::test(Index::class)
            ->call('delete', $bidding->bidding_mode_id)
            ->assertDispatched('notify', type: 'error');

        $this->assertModelExists($bidding->mode);
    }

    public function test_finds_the_mode_for_a_value(): void
    {
        $this->seed(BiddingModeSeeder::class);

        $name = fn (BiddingType $type, float $value) => BiddingMode::forValue($type, $value)->pluck('name')->all();

        $this->assertSame(['Dispensa'], $name(BiddingType::PurchaseServices, 10000));
        $this->assertSame(['Convite'], $name(BiddingType::PurchaseServices, 17600.01));
        $this->assertSame(['Tomada de Preços'], $name(BiddingType::ConstructionEngineering, 1000000));
        $this->assertSame(['Concorrência'], $name(BiddingType::PurchaseServices, 5000000));
    }
}
