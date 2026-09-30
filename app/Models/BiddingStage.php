<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Passagem de uma licitação por uma etapa do processo.
 */
class BiddingStage extends Model
{
    protected $fillable = [
        'bidding_step_id',
        'secretary_id',
        'professional_id',
        'started_at',
        'completed_at',
        'page_number',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'completed_at' => 'date',
        ];
    }

    public function bidding(): BelongsTo
    {
        return $this->belongsTo(Bidding::class);
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(BiddingStep::class, 'bidding_step_id');
    }

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Secretary::class);
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function previous(): ?self
    {
        return static::where('bidding_id', $this->bidding_id)->where('id', '<', $this->id)->latest('id')->first();
    }

    public function next(): ?self
    {
        return static::where('bidding_id', $this->bidding_id)->where('id', '>', $this->id)->oldest('id')->first();
    }

    /**
     * Corrige os dados da etapa. As datas são encadeadas: a conclusão desta
     * etapa é o início da seguinte (ou a finalização da licitação).
     */
    public function revise(array $data): void
    {
        DB::transaction(function () use ($data) {
            $this->update($data);

            if (! $this->isCompleted()) {
                return;
            }

            if ($next = $this->next()) {
                $next->update(['started_at' => $this->completed_at]);
            } elseif ($this->bidding->isCompleted()) {
                $this->bidding->update(['completed_at' => $this->completed_at]);
            }
        });
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Dias corridos na etapa (até hoje, se ainda estiver em andamento).
     */
    public function days(): int
    {
        return (int) $this->started_at->diffInDays($this->completed_at ?? today());
    }
}
