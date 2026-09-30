<?php

namespace App\Models;

use App\Enums\BiddingType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function biddings(): HasMany
    {
        return $this->hasMany(Bidding::class);
    }

    public function scopeEnabled(Builder $query): void
    {
        $query->where('enabled', true);
    }

    /**
     * Modalidades cuja faixa de valor do tipo informado contém o valor.
     * Modalidades sem faixa definida (pregões) ficam de fora.
     */
    public function scopeForValue(Builder $query, BiddingType $type, string|float $value): void
    {
        $min = $type->minimumColumn();
        $max = $type->maximumColumn();

        $query->where(fn ($query) => $query->whereNotNull($min)->orWhereNotNull($max))
            ->where(fn ($query) => $query->whereNull($min)->orWhere($min, '<=', $value))
            ->where(fn ($query) => $query->whereNull($max)->orWhere($max, '>=', $value));
    }
}
