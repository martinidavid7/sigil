<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Professionals\Index;
use App\Livewire\Secretaries\Index as SecretariesIndex;
use App\Models\Bidding;
use App\Models\BiddingStep;
use App\Models\CityHall;
use App\Models\Professional;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfessionalsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CityHall::factory()->create();
        $this->actingAs(User::factory()->create());
    }

    public function test_creates_a_professional_in_a_secretary(): void
    {
        $secretary = Secretary::factory()->create();

        Livewire::test(Index::class)
            ->call('create')
            ->set('form.secretary_id', (string) $secretary->id)
            ->set('form.name', 'Maria Souza')
            ->set('form.role', 'Pregoeira')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('professionals', [
            'secretary_id' => $secretary->id,
            'name' => 'Maria Souza',
            'role' => 'Pregoeira',
        ]);
    }

    public function test_validates_required_fields_and_duplicates_in_the_same_secretary(): void
    {
        $existing = Professional::factory()->create(['name' => 'João Lima']);

        Livewire::test(Index::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors(['form.secretary_id' => 'required', 'form.name' => 'required'])
            ->set('form.secretary_id', (string) $existing->secretary_id)
            ->set('form.name', 'João Lima')
            ->call('save')
            ->assertHasErrors(['form.name' => 'unique']);
    }

    public function test_filters_by_secretary_and_search(): void
    {
        $ana = Professional::factory()->create(['name' => 'Ana Prado']);
        $bruno = Professional::factory()->create(['name' => 'Bruno Reis']);

        Livewire::test(Index::class)
            ->set('secretary', (string) $ana->secretary_id)
            ->assertSee('Ana Prado')
            ->assertDontSee('Bruno Reis')
            ->set('secretary', '')
            ->set('search', 'Bruno')
            ->assertSee('Bruno Reis')
            ->assertDontSee('Ana Prado');
    }

    public function test_professionals_and_secretaries_in_use_cannot_be_deleted(): void
    {
        BiddingStep::factory()->create();
        $professional = Professional::factory()->create();
        Bidding::factory()->create()->begin([
            'started_at' => today()->toDateString(),
            'secretary_id' => $professional->secretary_id,
            'professional_id' => $professional->id,
        ]);

        Livewire::test(Index::class)
            ->call('delete', $professional->id)
            ->assertDispatched('notify', type: 'error');

        Livewire::test(SecretariesIndex::class)
            ->call('delete', $professional->secretary_id)
            ->assertDispatched('notify', type: 'error');

        $this->assertModelExists($professional);
        $this->assertModelExists($professional->secretary);
    }

    public function test_deleting_a_secretary_removes_its_unused_professionals(): void
    {
        $professional = Professional::factory()->create();

        Livewire::test(SecretariesIndex::class)->call('delete', $professional->secretary_id);

        $this->assertModelMissing($professional);
    }
}
