<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BiddingMode extends Model
{
    /** @use HasFactory<\Database\Factories\BiddingModeFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'deadline',
        'purchase_services_minimum_value',
        'purchase_services_maximum_value',
        'construction_engineering_minimum_value',
        'construction_engineering_maximum_value',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'purchase_services_minimum_value' => 'decimal:2',
            'purchase_services_maximum_value' => 'decimal:2',
            'construction_engineering_minimum_value' => 'decimal:2',
            'construction_engineering_maximum_value' => 'decimal:2',
            'enabled' => 'boolean',
        ];
    }

    public function steps(): BelongsToMany
    {
        return $this->belongsToMany(BiddingStep::class, 'bidding_mode_step')
            ->orderBy('position');
    }

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enabled', true);
    }
}
