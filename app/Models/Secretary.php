<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretary extends Model
{
    /** @use HasFactory<\Database\Factories\SecretaryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'responsible_name',
        'phone',
        'address',
        'number',
        'neighborhood',
        'zip_code',
    ];
}
