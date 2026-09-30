<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiddingStep extends Model
{
    /** @use HasFactory<\Database\Factories\BiddingStepFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'position',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'enabled' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Novas etapas entram no fim da sequência.
        static::creating(function (BiddingStep $step) {
            if (! $step->position) {
                $step->position = (static::max('position') ?? 0) + 1;
            }
        });
    }

    public function stages(): HasMany
    {
        return $this->hasMany(BiddingStage::class);
    }

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enabled', true);
    }

    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position')->orderBy('id');
    }
}
