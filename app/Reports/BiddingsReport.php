<?php

namespace App\Reports;

use App\Models\Bidding;
use App\Models\BiddingMode;
use App\Models\Secretary;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;

/**
 * Relatório de processos: licitações com a etapa em que cada uma está.
 */
class BiddingsReport
{
    public const STATUSES = [
        'andamento' => 'Em andamento',
        'concluidas' => 'Concluídas',
        'todas' => 'Todas',
    ];

    /**
     * @param  array{status?: string, bidding_mode_id?: ?int, year?: ?int, secretary_id?: ?int}  $filters
     */
    public function __construct(private array $filters) {}

    public static function rules(): array
    {
        return [
            'status' => ['nullable', Rule::in(array_keys(self::STATUSES))],
            'bidding_mode_id' => ['nullable', 'integer', Rule::exists('bidding_modes', 'id')],
            'year' => ['nullable', 'integer', 'between:2000,2100'],
            'secretary_id' => ['nullable', 'integer', Rule::exists('secretaries', 'id')],
        ];
    }

    public function title(): string
    {
        return 'Relatório de processos';
    }

    /**
     * @return Collection<int, Bidding>
     */
    public function rows(): Collection
    {
        $status = $this->filters['status'] ?? 'todas';

        return Bidding::query()
            ->with(['mode', 'stages', 'currentStage.step', 'currentStage.secretary', 'currentStage.professional'])
            ->when($status === 'andamento', fn ($query) => $query->inProgress())
            ->when($status === 'concluidas', fn ($query) => $query->completed())
            ->when($this->filters['bidding_mode_id'] ?? null, fn ($query, $id) => $query->where('bidding_mode_id', $id))
            ->when($this->filters['year'] ?? null, fn ($query, $year) => $query->where('year', $year))
            ->when($this->filters['secretary_id'] ?? null, fn ($query, $id) => $query->whereHas(
                'currentStage', fn ($query) => $query->where('secretary_id', $id),
            ))
            ->orderBy('year')
            ->orderBy('number')
            ->get();
    }

    /**
     * Filtros aplicados, para o cabeçalho do relatório.
     *
     * @return array<string, string>
     */
    public function appliedFilters(): array
    {
        return array_filter([
            'Situação' => self::STATUSES[$this->filters['status'] ?? 'todas'],
            'Modalidade' => BiddingMode::find($this->filters['bidding_mode_id'] ?? null)?->name,
            'Ano' => $this->filters['year'] ?? null,
            'Secretaria atual' => Secretary::find($this->filters['secretary_id'] ?? null)?->name,
        ]);
    }
}
