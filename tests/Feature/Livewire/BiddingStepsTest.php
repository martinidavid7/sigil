<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BiddingSteps\Index;
use App\Models\BiddingStep;
use App\Models\CityHall;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BiddingStepsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CityHall::factory()->create();
        $this->actingAs(User::factory()->create());
    }

    public function test_new_steps_go_to_the_end_of_the_sequence(): void
    {
        BiddingStep::factory()->create(['name' => 'Protocolo']);

        Livewire::test(Index::class)
            ->call('create')
            ->set('form.name', 'Ofício')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(
            ['Protocolo', 'Ofício'],
            BiddingStep::ordered()->pluck('name')->all(),
        );
    }

    public function test_reorders_steps(): void
    {
        [$first, $second, $third] = BiddingStep::factory()
            ->forEachSequence(['name' => 'A'], ['name' => 'B'], ['name' => 'C'])
            ->create();

        Livewire::test(Index::class)->call('moveUp', $third->id);
        $this->assertSame(['A', 'C', 'B'], BiddingStep::ordered()->pluck('name')->all());

        Livewire::test(Index::class)->call('moveDown', $first->id);
        $this->assertSame(['C', 'A', 'B'], BiddingStep::ordered()->pluck('name')->all());
    }

    public function test_moving_the_first_step_up_does_nothing(): void
    {
        $first = BiddingStep::factory()->create(['name' => 'A']);
        BiddingStep::factory()->create(['name' => 'B']);

        Livewire::test(Index::class)->call('moveUp', $first->id);

        $this->assertSame(['A', 'B'], BiddingStep::ordered()->pluck('name')->all());
    }

    public function test_disables_and_filters_steps(): void
    {
        $active = BiddingStep::factory()->create(['name' => 'Etapa ativa']);
        $step = BiddingStep::factory()->create(['name' => 'Etapa desativada']);

        Livewire::test(Index::class)
            ->call('toggle', $step->id)
            ->assertSee($active->name)
            ->assertDontSee($step->name)
            ->set('status', 'inativas')
            ->assertSee($step->name)
            ->assertDontSee($active->name);

        $this->assertFalse($step->fresh()->enabled);
    }
}
