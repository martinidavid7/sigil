<?php

namespace Tests\Feature;

use App\Livewire\Reports\Index;
use App\Models\Bidding;
use App\Models\BiddingStep;
use App\Models\CityHall;
use App\Models\Professional;
use App\Models\User;
use App\Reports\BiddingsReport;
use App\Reports\StagesReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    private Professional $professional;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo('2026-09-30');

        CityHall::factory()->create();
        $this->actingAs(User::factory()->create());
        $this->professional = Professional::factory()->create();
        BiddingStep::factory()->create(['name' => 'Protocolo']);
        BiddingStep::factory()->create(['name' => 'Ofício']);
    }

    private function bidding(array $attributes = [], string $startedAt = '2026-09-01', ?Professional $professional = null): Bidding
    {
        $professional ??= $this->professional;

        $bidding = Bidding::factory()->create($attributes);
        $bidding->begin([
            'started_at' => $startedAt,
            'secretary_id' => $professional->secretary_id,
            'professional_id' => $professional->id,
        ]);

        return $bidding;
    }

    private function complete(Bidding $bidding, string $date): void
    {
        $bidding->completeCurrentStage(
            ['completed_at' => $date, 'page_number' => '3', 'notes' => null],
            ['secretary_id' => $this->professional->secretary_id, 'professional_id' => $this->professional->id],
        );
    }

    public function test_reports_page_builds_urls_with_the_filled_filters(): void
    {
        Livewire::test(Index::class)
            ->set('biddings.status', 'concluidas')
            ->set('biddings.year', '2026')
            ->assertSee(route('reports.biddings', ['status' => 'concluidas', 'year' => '2026']))
            ->assertSee(route('reports.stages', ['from' => '2026-09-01', 'to' => '2026-09-30', 'status' => 'todas']));
    }

    public function test_generates_the_pdfs(): void
    {
        $bidding = $this->bidding();

        foreach ([
            route('reports.biddings', ['status' => 'todas']),
            route('reports.stages', ['from' => '2026-09-01']),
            route('reports.bidding', $bidding),
        ] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertHeader('content-type', 'application/pdf');
        }
    }

    public function test_rejects_invalid_filters(): void
    {
        $this->get(route('reports.stages', ['from' => '2026-09-10', 'to' => '2026-09-01']))
            ->assertSessionHasErrors('to');
    }

    public function test_biddings_report_filters(): void
    {
        $other = Professional::factory()->create();
        $open = $this->bidding(['year' => 2026]);
        $finished = $this->bidding(['year' => 2025]);
        $elsewhere = $this->bidding(['year' => 2026], professional: $other);
        $finished->completeCurrentStage(['completed_at' => '2026-09-02', 'page_number' => null, 'notes' => null], [
            'secretary_id' => $this->professional->secretary_id, 'professional_id' => $this->professional->id,
        ]);
        $this->complete($finished->fresh(), '2026-09-03');

        $ids = fn (array $filters) => (new BiddingsReport($filters))->rows()->modelKeys();

        $this->assertEqualsCanonicalizing([$open->id, $elsewhere->id], $ids(['status' => 'andamento']));
        $this->assertSame([$finished->id], $ids(['status' => 'concluidas']));
        $this->assertSame([$finished->id], $ids(['year' => 2025]));
        $this->assertSame([$elsewhere->id], $ids(['secretary_id' => $other->secretary_id]));
        $this->assertSame([$open->bidding_mode_id], array_values(array_unique(
            (new BiddingsReport(['bidding_mode_id' => $open->bidding_mode_id]))->rows()->pluck('bidding_mode_id')->all(),
        )));
    }

    public function test_stages_report_lists_stages_active_in_the_period(): void
    {
        $bidding = $this->bidding(startedAt: '2026-08-01');
        $this->complete($bidding, '2026-08-20'); // Protocolo: 01/08 a 20/08; Ofício desde 20/08

        $steps = fn (array $filters) => (new StagesReport($filters))->rows()->pluck('step.name')->all();

        $this->assertSame(['Protocolo', 'Ofício'], $steps(['from' => '2026-08-10', 'to' => '2026-08-31']));
        $this->assertSame(['Ofício'], $steps(['from' => '2026-09-01']));
        $this->assertSame(['Protocolo'], $steps(['to' => '2026-08-15']));
        $this->assertSame(['Ofício'], $steps(['status' => 'andamento']));
        $this->assertSame(['Protocolo'], $steps(['status' => 'concluidas']));
    }

    public function test_stages_report_averages_completed_steps(): void
    {
        $this->complete($this->bidding(startedAt: '2026-09-01'), '2026-09-05');
        $this->complete($this->bidding(startedAt: '2026-09-01'), '2026-09-08');

        $report = new StagesReport([]);
        $averages = $report->averageDaysByStep($report->rows());

        $this->assertCount(1, $averages);
        $this->assertSame('Protocolo', $averages[0]['step']->name);
        $this->assertSame(2, $averages[0]['count']);
        $this->assertSame(5.5, $averages[0]['average']);
    }
}
