<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Secretaries\Index;
use App\Models\CityHall;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SecretariesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CityHall::factory()->create();
        $this->actingAs(User::factory()->create());
    }

    public function test_page_renders_the_component(): void
    {
        $this->get(route('secretaries.index'))
            ->assertOk()
            ->assertSeeLivewire(Index::class);
    }

    public function test_creates_a_secretary(): void
    {
        Livewire::test(Index::class)
            ->call('create')
            ->assertSet('showModal', true)
            ->set('form.name', 'Secretaria de Saúde')
            ->set('form.responsible_name', 'Ana Lima')
            ->set('form.phone', '(19) 99876-5432')
            ->set('form.address', 'Rua das Flores')
            ->set('form.number', '12')
            ->set('form.neighborhood', 'Centro')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('showModal', false)
            ->assertDispatched('notify');

        $this->assertDatabaseHas('secretaries', ['name' => 'Secretaria de Saúde', 'responsible_name' => 'Ana Lima']);
    }

    public function test_edits_a_secretary(): void
    {
        $secretary = Secretary::factory()->create();

        Livewire::test(Index::class)
            ->call('edit', $secretary->id)
            ->assertSet('form.name', $secretary->name)
            ->set('form.responsible_name', 'Novo Responsável')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Novo Responsável', $secretary->fresh()->responsible_name);
    }

    public function test_name_must_be_unique(): void
    {
        Secretary::factory()->create(['name' => 'Secretaria de Obras']);

        Livewire::test(Index::class)
            ->call('create')
            ->set('form.name', 'Secretaria de Obras')
            ->call('save')
            ->assertHasErrors(['form.name' => 'unique', 'form.responsible_name' => 'required']);
    }

    public function test_deletes_a_secretary(): void
    {
        $secretary = Secretary::factory()->create();

        Livewire::test(Index::class)->call('delete', $secretary->id);

        $this->assertModelMissing($secretary);
    }

    public function test_searches_by_name_or_responsible(): void
    {
        Secretary::factory()->create(['name' => 'Secretaria de Educação', 'responsible_name' => 'Carla']);
        Secretary::factory()->create(['name' => 'Secretaria de Finanças', 'responsible_name' => 'Roberto']);

        Livewire::test(Index::class)
            ->set('search', 'Roberto')
            ->assertSee('Secretaria de Finanças')
            ->assertDontSee('Secretaria de Educação');
    }
}
