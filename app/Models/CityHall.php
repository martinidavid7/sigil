<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityHall extends Model
{
   
    protected $fillable = ['city_hall', 
                            'mayor',
                            'address',
                            'number',
                            'phone',
                            'neighborhood',
                            'zip_code',
                            'cnpj',
                            'inscricao_estadual',
                            '_token', 
                            '_method'];
}