<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Professional extends Model
{
    /** @use HasFactory<\Database\Factories\ProfessionalFactory> */
    use HasFactory;

    protected $fillable = [
        'secretary_id',
        'name',
        'role',
    ];

    public function secretary(): BelongsTo
    {
        return $this->belongsTo(Secretary::class);
    }

    public function stages(): HasMany
    {
        return $this->hasMany(BiddingStage::class);
    }
}
