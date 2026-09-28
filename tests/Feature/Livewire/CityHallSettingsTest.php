<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CityHalls\Settings;
use App\Models\CityHall;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CityHallSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_guests_are_redirected_to_login(): void
    {
        auth()->logout();

        $this->get(route('city-hall'))->assertRedirect(route('login'));
    }

    public function test_other_registers_require_a_configured_city_hall(): void
    {
        $this->get(route('secretaries.index'))->assertRedirect(route('city-hall'));
    }

    public function test_creates_the_city_hall_on_first_setup(): void
    {
        Livewire::test(Settings::class)
            ->set('form.name', 'Prefeitura Municipal de Vila Esperança')
            ->set('form.mayor', 'Maria Souza')
            ->set('form.cnpj', '12.345.678/0001-90')
            ->set('form.address', 'Praça Central')
            ->set('form.number', '100')
            ->set('form.neighborhood', 'Centro')
            ->set('form.city', 'Vila Esperança')
            ->set('form.zip_code', '13490-000')
            ->set('form.phone', '(19) 3556-9900')
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('city-hall'));

        $this->assertDatabaseHas('city_halls', ['name' => 'Prefeitura Municipal de Vila Esperança', 'city' => 'Vila Esperança']);
        $this->get(route('secretaries.index'))->assertOk();
    }

    public function test_updates_the_existing_city_hall_without_creating_another(): void
    {
        $cityHall = CityHall::factory()->create();

        Livewire::test(Settings::class)
            ->assertSet('form.name', $cityHall->name)
            ->set('form.mayor', 'João Pereira')
            ->call('save')
            ->assertHasNoErrors()
            ->assertNoRedirect()
            ->assertDispatched('notify');

        $this->assertSame(1, CityHall::count());
        $this->assertSame('João Pereira', $cityHall->fresh()->mayor);
    }

    public function test_validates_required_fields_and_formats(): void
    {
        Livewire::test(Settings::class)
            ->set('form.cnpj', '123')
            ->set('form.zip_code', '13490000')
            ->call('save')
            ->assertHasErrors([
                'form.name' => 'required',
                'form.mayor' => 'required',
                'form.cnpj' => 'regex',
                'form.zip_code' => 'regex',
            ]);

        $this->assertFalse(CityHall::isConfigured());
    }
}
