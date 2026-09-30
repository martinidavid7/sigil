<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\ChoosesResponsible;
use App\Models\Bidding;
use App\Models\BiddingMode;
use App\Models\BiddingStep;
use App\Reports\BiddingsReport;
use App\Reports\StagesReport;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Filtros dos relatórios. O PDF é gerado pelo ReportController, aberto em
 * outra aba com os filtros na URL.
 */
#[Title('Relatórios')]
class Index extends Component
{
    use ChoosesResponsible;

    public array $biddings = [
        'status' => 'andamento',
        'bidding_mode_id' => '',
        'year' => '',
        'secretary_id' => '',
    ];

    public array $stages = [
        'from' => '',
        'to' => '',
        'status' => 'todas',
        'bidding_step_id' => '',
        'secretary_id' => '',
        'professional_id' => '',
    ];

    public function mount(): void
    {
        $this->stages['from'] = today()->startOfMonth()->toDateString();
        $this->stages['to'] = today()->toDateString();
    }

    public function reportUrl(string $report): string
    {
        return route("reports.{$report}", array_filter($this->{$report}, fn ($value) => $value !== ''));
    }

    public function render()
    {
        return view('livewire.reports.index', [
            'biddingStatuses' => BiddingsReport::STATUSES,
            'stageStatuses' => StagesReport::STATUSES,
            'modes' => BiddingMode::orderBy('id')->pluck('name', 'id'),
            'years' => Bidding::distinct()->orderByDesc('year')->pluck('year', 'year'),
            'steps' => BiddingStep::ordered()->pluck('name', 'id'),
        ]);
    }
}
