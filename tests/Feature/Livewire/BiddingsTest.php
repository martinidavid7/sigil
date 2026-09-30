<?php

namespace Tests\Feature\Livewire;

use App\Enums\BiddingType;
use App\Livewire\Biddings\Index;
use App\Livewire\Biddings\Show;
use App\Models\Bidding;
use App\Models\BiddingMode;
use App\Models\BiddingStep;
use App\Models\CityHall;
use App\Models\Professional;
use App\Models\User;
use Database\Seeders\BiddingModeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BiddingsTest extends TestCase
{
    use RefreshDatabase;

    private Professional $professional;

    protected function setUp(): void
    {
        parent::setUp();

        // As datas de conclusão não podem ser futuras.
        $this->travelTo('2026-09-30');

        CityHall::factory()->create();
        $this->actingAs(User::factory()->create());
        $this->professional = Professional::factory()->create();
    }

    /**
     * Cria as etapas na ordem informada e devolve-as indexadas pelo nome.
     */
    private function steps(string ...$names)
    {
        return collect($names)->mapWithKeys(fn ($name) => [$name => BiddingStep::factory()->create(['name' => $name])]);
    }

    private function openBidding(string $startedAt = '2026-09-01'): Bidding
    {
        $bidding = Bidding::factory()->create();
        $bidding->begin([
            'started_at' => $startedAt,
            'secretary_id' => $this->professional->secretary_id,
            'professional_id' => $this->professional->id,
        ]);

        return $bidding;
    }

    private function completeCurrent(Bidding $bidding, ?Professional $next = null, string $date = '2026-09-10')
    {
        $next ??= Professional::factory()->create();

        return Livewire::test(Show::class, ['bidding' => $bidding])
            ->call('openCompletion')
            ->set('completion.completed_at', $date)
            ->set('completion.page_number', '12')
            ->set('completion.notes', 'Documentação conferida.')
            ->set('completion.secretary_id', (string) $next->secretary_id)
            ->set('completion.professional_id', (string) $next->id)
            ->call('complete');
    }

    public function test_opens_a_bidding_on_the_first_active_step(): void
    {
        $steps = $this->steps('Protocolo', 'Ofício');
        $steps['Protocolo']->update(['enabled' => false]);
        $mode = BiddingMode::factory()->create();

        $component = Livewire::test(Index::class)
            ->call('create')
            ->assertSet('form.number', '1')
            ->assertSet('form.year', (string) today()->year)
            ->set('form.subject', 'Aquisição de material de expediente')
            ->set('form.type', BiddingType::PurchaseServices->value)
            ->set('form.estimated_value', '25.000,00')
            ->set('form.bidding_mode_id', (string) $mode->id)
            ->set('form.started_at', today()->toDateString())
            ->set('form.secretary_id', (string) $this->professional->secretary_id)
            ->set('form.professional_id', (string) $this->professional->id)
            ->call('save')
            ->assertHasNoErrors();

        $bidding = Bidding::sole();
        $component->assertRedirect(route('biddings.show', $bidding));

        $this->assertSame('25000.00', $bidding->estimated_value);
        $this->assertSame('001/'.today()->year, $bidding->code);
        $this->assertSame($steps['Ofício']->id, $bidding->currentStage->bidding_step_id);
        $this->assertSame($this->professional->id, $bidding->currentStage->professional_id);
    }

    public function test_professional_must_belong_to_the_chosen_secretary(): void
    {
        $this->steps('Protocolo');
        $other = Professional::factory()->create();

        Livewire::test(Index::class)
            ->call('create')
            ->set('form.subject', 'Objeto')
            ->set('form.bidding_mode_id', (string) BiddingMode::factory()->create()->id)
            ->set('form.secretary_id', (string) $this->professional->secretary_id)
            ->set('form.professional_id', (string) $other->id)
            ->call('save')
            ->assertHasErrors(['form.professional_id' => 'exists']);

        $this->assertDatabaseCount('biddings', 0);
    }

    public function test_changing_the_secretary_clears_the_professional(): void
    {
        $this->steps('Protocolo');
        $other = Professional::factory()->create();

        Livewire::test(Index::class)
            ->call('create')
            ->set('form.secretary_id', (string) $this->professional->secretary_id)
            ->set('form.professional_id', (string) $this->professional->id)
            ->set('form.secretary_id', (string) $other->secretary_id)
            ->assertSet('form.professional_id', '');
    }

    public function test_cannot_open_a_bidding_without_active_steps(): void
    {
        BiddingStep::factory()->disabled()->create();

        Livewire::test(Index::class)
            ->call('create')
            ->assertSet('showModal', false)
            ->assertDispatched('notify', type: 'error');
    }

    public function test_suggests_the_mode_by_value_range(): void
    {
        $this->steps('Protocolo');
        $this->seed(BiddingModeSeeder::class);
        $convite = BiddingMode::where('name', 'Convite')->sole();

        Livewire::test(Index::class)
            ->call('create')
            ->set('form.type', BiddingType::PurchaseServices->value)
            ->set('form.estimated_value', '150.000,00')
            ->assertSee('a modalidade indicada é')
            ->call('applySuggestedMode')
            ->assertSet('form.bidding_mode_id', (string) $convite->id);
    }

    public function test_completing_a_stage_starts_the_next_one_with_the_new_responsible(): void
    {
        $steps = $this->steps('Protocolo', 'Ofício', 'Requisição');
        $bidding = $this->openBidding();
        $next = Professional::factory()->create();

        $this->completeCurrent($bidding, $next)->assertHasNoErrors();

        [$protocol, $letter] = $bidding->fresh()->stages;

        $this->assertSame('2026-09-10', $protocol->completed_at->toDateString());
        $this->assertSame('12', $protocol->page_number);
        $this->assertSame('Documentação conferida.', $protocol->notes);

        $this->assertSame($steps['Ofício']->id, $letter->bidding_step_id);
        $this->assertSame('2026-09-10', $letter->started_at->toDateString());
        $this->assertSame($next->secretary_id, $letter->secretary_id);
        $this->assertSame($next->id, $letter->professional_id);
        $this->assertNull($letter->completed_at);
    }

    public function test_next_responsible_is_required_while_there_are_steps_left(): void
    {
        $this->steps('Protocolo', 'Ofício');
        $bidding = $this->openBidding();

        Livewire::test(Show::class, ['bidding' => $bidding])
            ->call('openCompletion')
            ->assertSet('completion.hasNextStep', true)
            ->set('completion.completed_at', '2026-09-10')
            ->call('complete')
            ->assertHasErrors(['completion.secretary_id' => 'required', 'completion.professional_id' => 'required']);

        $this->assertNull($bidding->currentStage->completed_at);
    }

    public function test_completion_date_cannot_be_before_the_stage_start(): void
    {
        $this->steps('Protocolo', 'Ofício');
        $bidding = $this->openBidding('2026-09-01');

        $this->completeCurrent($bidding, date: '2026-08-31')
            ->assertHasErrors(['completion.completed_at' => 'after_or_equal'])
            ->assertSee('não pode ser anterior ao início da etapa (01/09/2026)');
    }

    public function test_completing_the_last_stage_finishes_the_bidding(): void
    {
        $this->steps('Protocolo');
        $bidding = $this->openBidding();

        Livewire::test(Show::class, ['bidding' => $bidding])
            ->call('openCompletion')
            ->assertSet('completion.hasNextStep', false)
            ->assertSee('Esta é a última etapa')
            ->set('completion.completed_at', '2026-09-05')
            ->call('complete')
            ->assertHasNoErrors();

        $bidding->refresh();
        $this->assertTrue($bidding->isCompleted());
        $this->assertSame('2026-09-05', $bidding->completed_at->toDateString());
        $this->assertNull($bidding->currentStage);
        $this->assertCount(0, Bidding::inProgress()->get());
    }

    public function test_follows_the_steps_currently_enabled(): void
    {
        $steps = $this->steps('Protocolo', 'Ofício', 'Requisição');
        $bidding = $this->openBidding();

        // Com a licitação em andamento, uma etapa é desativada e outra é criada no fim.
        $steps['Ofício']->update(['enabled' => false]);
        $new = BiddingStep::factory()->create(['name' => 'Homologação']);

        $this->assertSame(['Requisição', 'Homologação'], $bidding->pendingSteps()->pluck('name')->all());

        $this->completeCurrent($bidding)->assertHasNoErrors();
        $this->assertSame($steps['Requisição']->id, $bidding->fresh()->currentStage->bidding_step_id);

        $this->completeCurrent($bidding, date: '2026-09-15')->assertHasNoErrors();
        $this->assertSame($new->id, $bidding->fresh()->currentStage->bidding_step_id);

        // A etapa desativada continua fora; o histórico percorrido não muda.
        $this->assertSame(
            ['Protocolo', 'Requisição', 'Homologação'],
            $bidding->fresh()->stages->pluck('step.name')->all(),
        );
    }

    public function test_steps_moved_before_the_current_one_are_not_revisited(): void
    {
        $steps = $this->steps('Protocolo', 'Ofício', 'Requisição');
        $bidding = $this->openBidding();
        $this->completeCurrent($bidding)->assertHasNoErrors();

        // Requisição passa para antes do Ofício, que está em andamento.
        $steps['Requisição']->update(['position' => 0]);

        $this->assertTrue($bidding->fresh()->pendingSteps()->isEmpty());
    }

    public function test_reopens_the_last_completed_stage(): void
    {
        $this->steps('Protocolo', 'Ofício');
        $bidding = $this->openBidding();
        $this->completeCurrent($bidding)->assertHasNoErrors();

        Livewire::test(Show::class, ['bidding' => $bidding->fresh()])
            ->call('reopen')
            ->assertDispatched('notify');

        $stages = $bidding->fresh()->stages;
        $this->assertCount(1, $stages);
        $this->assertNull($stages->first()->completed_at);
        $this->assertSame('12', $stages->first()->page_number);
    }

    public function test_reopening_a_finished_bidding_puts_it_back_in_progress(): void
    {
        $this->steps('Protocolo');
        $bidding = $this->openBidding();
        $bidding->completeCurrentStage(['completed_at' => '2026-09-05', 'page_number' => null, 'notes' => null]);

        Livewire::test(Show::class, ['bidding' => $bidding->fresh()])->call('reopen');

        $this->assertFalse($bidding->fresh()->isCompleted());
        $this->assertNotNull($bidding->fresh()->currentStage);
    }

    public function test_edits_the_current_stage(): void
    {
        $this->steps('Protocolo', 'Ofício');
        $bidding = $this->openBidding('2026-09-01');
        $other = Professional::factory()->create();

        Livewire::test(Show::class, ['bidding' => $bidding])
            ->call('editStage', $bidding->currentStage->id)
            ->assertSet('stageForm.professional_id', (string) $this->professional->id)
            ->set('stageForm.started_at', '2026-09-02')
            ->set('stageForm.secretary_id', (string) $other->secretary_id)
            ->set('stageForm.professional_id', (string) $other->id)
            ->set('stageForm.page_number', '5')
            ->set('stageForm.notes', 'Recebido pelo protocolo geral.')
            ->call('updateStage')
            ->assertHasNoErrors();

        $stage = $bidding->fresh()->currentStage;
        $this->assertSame('2026-09-02', $stage->started_at->toDateString());
        $this->assertSame($other->id, $stage->professional_id);
        $this->assertSame('5', $stage->page_number);
        $this->assertSame('Recebido pelo protocolo geral.', $stage->notes);
    }

    public function test_changing_a_completion_date_moves_the_next_stage_start(): void
    {
        $this->steps('Protocolo', 'Ofício');
        $bidding = $this->openBidding('2026-09-01');
        $this->completeCurrent($bidding, date: '2026-09-10')->assertHasNoErrors();
        [$protocol, $letter] = $bidding->fresh()->stages;

        Livewire::test(Show::class, ['bidding' => $bidding->fresh()])
            ->call('editStage', $protocol->id)
            ->set('stageForm.completed_at', '2026-09-08')
            ->call('updateStage')
            ->assertHasNoErrors();

        $this->assertSame('2026-09-08', $protocol->fresh()->completed_at->toDateString());
        $this->assertSame('2026-09-08', $letter->fresh()->started_at->toDateString());
    }

    public function test_stage_dates_stay_in_sequence(): void
    {
        $this->steps('Protocolo', 'Ofício', 'Requisição');
        $bidding = $this->openBidding('2026-09-01');
        $this->completeCurrent($bidding, date: '2026-09-10')->assertHasNoErrors();
        $this->completeCurrent($bidding->fresh(), date: '2026-09-15')->assertHasNoErrors();
        [$protocol, $letter] = $bidding->fresh()->stages;

        // A conclusão não pode passar da conclusão da etapa seguinte...
        Livewire::test(Show::class, ['bidding' => $bidding->fresh()])
            ->call('editStage', $protocol->id)
            ->set('stageForm.completed_at', '2026-09-16')
            ->call('updateStage')
            ->assertHasErrors(['stageForm.completed_at' => 'before_or_equal'])
            ->assertSee('posterior à conclusão da etapa seguinte (15/09/2026)');

        // ...e o início só é editável na primeira etapa.
        Livewire::test(Show::class, ['bidding' => $bidding->fresh()])
            ->call('editStage', $letter->id)
            ->set('stageForm.started_at', '2026-09-01')
            ->call('updateStage')
            ->assertHasNoErrors();

        $this->assertSame('2026-09-10', $letter->fresh()->started_at->toDateString());
    }

    public function test_editing_the_last_stage_of_a_finished_bidding_moves_its_end_date(): void
    {
        $this->steps('Protocolo');
        $bidding = $this->openBidding('2026-09-01');
        $bidding->completeCurrentStage(['completed_at' => '2026-09-05', 'page_number' => null, 'notes' => null]);

        Livewire::test(Show::class, ['bidding' => $bidding->fresh()])
            ->call('editStage', $bidding->stages->first()->id)
            ->set('stageForm.completed_at', '2026-09-03')
            ->call('updateStage')
            ->assertHasNoErrors();

        $this->assertSame('2026-09-03', $bidding->fresh()->completed_at->toDateString());
    }

    public function test_updates_and_deletes_a_bidding(): void
    {
        $this->steps('Protocolo');
        $bidding = $this->openBidding();

        Livewire::test(Show::class, ['bidding' => $bidding])
            ->call('edit')
            ->assertSet('form.number', (string) $bidding->number)
            ->set('form.subject', 'Objeto corrigido')
            ->call('update')
            ->assertHasNoErrors();

        $this->assertSame('Objeto corrigido', $bidding->fresh()->subject);

        Livewire::test(Show::class, ['bidding' => $bidding->fresh()])
            ->call('delete')
            ->assertRedirect(route('biddings.index'));

        $this->assertModelMissing($bidding);
        $this->assertDatabaseCount('bidding_stages', 0);
    }

    public function test_lists_and_filters_biddings(): void
    {
        $this->steps('Protocolo');
        $open = $this->openBidding();
        $open->update(['number' => 7, 'subject' => 'Compra de uniformes']);
        $finished = $this->openBidding();
        $finished->update(['number' => 8, 'subject' => 'Compra de medicamentos']);
        $finished->completeCurrentStage(['completed_at' => '2026-09-05', 'page_number' => null, 'notes' => null]);

        Livewire::test(Index::class)
            ->assertSee('Compra de uniformes')
            ->assertDontSee('Compra de medicamentos')
            ->set('status', 'concluidas')
            ->assertSee('Compra de medicamentos')
            ->assertDontSee('Compra de uniformes')
            ->set('status', 'todas')
            ->set('search', '7/'.$open->year)
            ->assertSee('Compra de uniformes')
            ->assertDontSee('Compra de medicamentos')
            ->set('search', 'medicamentos')
            ->assertSee('Compra de medicamentos');
    }

    public function test_shows_the_process_map(): void
    {
        $this->steps('Protocolo', 'Ofício', 'Requisição');
        $bidding = $this->openBidding();
        $this->completeCurrent($bidding)->assertHasNoErrors();

        $this->get(route('biddings.show', $bidding))
            ->assertOk()
            ->assertSee('1 de 3 etapas concluídas')
            ->assertSeeInOrder(['Protocolo', '10/09/2026', 'Ofício', 'Em andamento', 'Requisição', 'Pendente']);
    }
}
