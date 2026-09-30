<?php

namespace App\Reports;

use App\Models\BiddingStage;
use App\Models\BiddingStep;
use App\Models\Professional;
use App\Models\Secretary;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Validation\Rule;

/**
 * Relatório de etapas: a passagem dos processos pelas etapas, com quem
 * esteve responsável e quanto tempo cada uma levou.
 */
class StagesReport
{
    public const STATUSES = [
        'todas' => 'Todas',
        'andamento' => 'Em andamento',
        'concluidas' => 'Concluídas',
    ];

    /**
     * @param  array{from?: ?string, to?: ?string, status?: string, secretary_id?: ?int, professional_id?: ?int, bidding_step_id?: ?int}  $filters
     */
    public function __construct(private array $filters) {}

    public static function rules(): array
    {
        return [
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'status' => ['nullable', Rule::in(array_keys(self::STATUSES))],
            'secretary_id' => ['nullable', 'integer', Rule::exists('secretaries', 'id')],
            'professional_id' => ['nullable', 'integer', Rule::exists('professionals', 'id')],
            'bidding_step_id' => ['nullable', 'integer', Rule::exists('bidding_steps', 'id')],
        ];
    }

    public function title(): string
    {
        return 'Relatório de etapas';
    }

    /**
     * Etapas que estiveram em andamento em algum momento do período.
     *
     * @return Collection<int, BiddingStage>
     */
    public function rows(): Collection
    {
        $status = $this->filters['status'] ?? 'todas';

        return BiddingStage::query()
            ->with(['bidding', 'step', 'secretary', 'professional'])
            ->when($this->filters['from'] ?? null, fn ($query, $from) => $query->where(
                fn ($query) => $query->whereNull('completed_at')->orWhere('completed_at', '>=', $from),
            ))
            ->when($this->filters['to'] ?? null, fn ($query, $to) => $query->where('started_at', '<=', $to))
            ->when($status === 'andamento', fn ($query) => $query->whereNull('completed_at'))
            ->when($status === 'concluidas', fn ($query) => $query->whereNotNull('completed_at'))
            ->when($this->filters['secretary_id'] ?? null, fn ($query, $id) => $query->where('secretary_id', $id))
            ->when($this->filters['professional_id'] ?? null, fn ($query, $id) => $query->where('professional_id', $id))
            ->when($this->filters['bidding_step_id'] ?? null, fn ($query, $id) => $query->where('bidding_step_id', $id))
            ->orderBy('started_at')
            ->orderBy('id')
            ->get();
    }

    /**
     * Tempo médio (em dias) das etapas concluídas, agrupado por etapa.
     *
     * @param  Collection<int, BiddingStage>  $rows
     * @return BaseCollection<int, array{step: BiddingStep, count: int, average: float}>
     */
    public function averageDaysByStep(Collection $rows): BaseCollection
    {
        return $rows->filter->isCompleted()
            ->groupBy('bidding_step_id')
            ->map(fn ($stages) => [
                'step' => $stages->first()->step,
                'count' => $stages->count(),
                'average' => round($stages->avg(fn (BiddingStage $stage) => $stage->days()), 1),
            ])
            ->sortBy(fn ($row) => $row['step']->position)
            ->values();
    }

    /**
     * @return array<string, string>
     */
    public function appliedFilters(): array
    {
        $from = $this->filters['from'] ?? null;
        $to = $this->filters['to'] ?? null;

        $period = match (true) {
            $from && $to => $this->date($from).' a '.$this->date($to),
            (bool) $from => 'a partir de '.$this->date($from),
            (bool) $to => 'até '.$this->date($to),
            default => null,
        };

        return array_filter([
            'Período' => $period,
            'Situação' => self::STATUSES[$this->filters['status'] ?? 'todas'],
            'Etapa' => BiddingStep::find($this->filters['bidding_step_id'] ?? null)?->name,
            'Secretaria' => Secretary::find($this->filters['secretary_id'] ?? null)?->name,
            'Profissional' => Professional::find($this->filters['professional_id'] ?? null)?->name,
        ]);
    }

    private function date(string $value): string
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
}
