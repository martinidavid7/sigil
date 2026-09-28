<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityHall extends Model
{
    /** @use HasFactory<\Database\Factories\CityHallFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'mayor',
        'cnpj',
        'state_registration',
        'address',
        'number',
        'neighborhood',
        'city',
        'zip_code',
        'phone',
    ];

    /**
     * O sistema atende a uma única prefeitura; os demais cadastros só são
     * liberados depois que ela estiver configurada.
     */
    public static function isConfigured(): bool
    {
        return static::query()->exists();
    }
}
