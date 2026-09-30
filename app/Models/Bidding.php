<?php

namespace App\Models;

use App\Enums\BiddingType;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use LogicException;

/**
 * Processo licitatório. Percorre, uma a uma, as etapas ativas do cadastro:
 * as etapas já iniciadas ficam gravadas em `bidding_stages`, e a próxima é
 * buscada no cadastro no momento em que a atual é concluída.
 */
class Bidding extends Model
{
    /** @use HasFactory<\Database\Factories\BiddingFactory> */
    use HasFactory;

    protected $fillable = [
        'number',
        'year',
        'subject',
        'bidding_mode_id',
        'type',
        'estimated_value',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'year' => 'integer',
            'type' => BiddingType::class,
            'estimated_value' => 'decimal:2',
            'completed_at' => 'date',
        ];
    }

    public function mode(): BelongsTo
    {
        return $this->belongsTo(BiddingMode::class, 'bidding_mode_id');
    }

    public function stages(): HasMany
    {
        return $this->hasMany(BiddingStage::class)->orderBy('id');
    }

    public function currentStage(): HasOne
    {
        return $this->hasOne(BiddingStage::class)->whereNull('completed_at');
    }

    /**
     * Número do processo no formato "001/2026".
     */
    protected function code(): Attribute
    {
        return Attribute::get(fn () => sprintf('%03d/%d', $this->number, $this->year));
    }

    public function scopeInProgress(Builder $query): void
    {
        $query->whereNull('completed_at');
    }

    public function scopeCompleted(Builder $query): void
    {
        $query->whereNotNull('completed_at');
    }

    public static function nextNumber(int $year): int
    {
        return (static::where('year', $year)->max('number') ?? 0) + 1;
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Etapas ativas do cadastro que a licitação ainda vai percorrer, na ordem
     * atual do cadastro. Etapas desativadas ou já percorridas ficam de fora.
     *
     * @return Collection<int, BiddingStep>
     */
    public function pendingSteps(): Collection
    {
        if ($this->isCompleted()) {
            return new Collection;
        }

        $lastStage = $this->stages()->with('step')->reorder()->latest('id')->first();

        return BiddingStep::enabled()
            ->whereNotIn('id', BiddingStage::where('bidding_id', $this->id)->select('bidding_step_id'))
            ->when($lastStage, fn ($query) => $query->where('position', '>', $lastStage->step->position))
            ->ordered()
            ->get();
    }

    /**
     * Abre a licitação na primeira etapa ativa do cadastro.
     *
     * @param  array{secretary_id: int, professional_id: int, started_at: string}  $stage
     */
    public function begin(array $stage): BiddingStage
    {
        $step = $this->pendingSteps()->first()
            ?? throw new DomainException('Não há etapas ativas cadastradas.');

        return $this->stages()->create([...$stage, 'bidding_step_id' => $step->id]);
    }

    /**
     * Conclui a etapa atual e inicia a próxima etapa ativa com os novos
     * responsáveis. Sem próxima etapa, a licitação é concluída.
     *
     * @param  array{completed_at: string, page_number: ?string, notes: ?string}  $completion
     * @param  array{secretary_id: int, professional_id: int}|null  $next
     * @return BiddingStage|null a etapa iniciada, ou null se a licitação foi concluída
     */
    public function completeCurrentStage(array $completion, ?array $next = null): ?BiddingStage
    {
        return DB::transaction(function () use ($completion, $next) {
            $current = $this->currentStage()->firstOrFail();
            $nextStep = $this->pendingSteps()->first();

            $current->update($completion);

            if (! $nextStep) {
                $this->update(['completed_at' => $completion['completed_at']]);

                return null;
            }

            if (! $next) {
                throw new LogicException('Informe os responsáveis pela próxima etapa.');
            }

            return $this->stages()->create([
                'bidding_step_id' => $nextStep->id,
                'secretary_id' => $next['secretary_id'],
                'professional_id' => $next['professional_id'],
                'started_at' => $completion['completed_at'],
            ]);
        });
    }

    public function canReopen(): bool
    {
        return $this->stages()->whereNotNull('completed_at')->exists();
    }

    /**
     * Desfaz a última conclusão: descarta a etapa que ela iniciou (se houver)
     * e volta a etapa concluída para "em andamento".
     */
    public function reopenLastStage(): BiddingStage
    {
        return DB::transaction(function () {
            $this->currentStage()->delete();

            $last = $this->stages()->whereNotNull('completed_at')->reorder()->latest('id')->firstOrFail();
            $last->update(['completed_at' => null]);

            $this->update(['completed_at' => null]);

            return $last;
        });
    }
}
