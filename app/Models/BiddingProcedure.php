<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingProcedure extends Model
{

    //protected $table = 'bidding_procedures';

    protected $fillable = [
        'mode',
        'deadline',
        'purchase_services_minimum_value',
        'purchase_services_maximum_value',
        'construction_engineering_minimum_value',
        'construction_engineering_maximum_value',
        'steps',
        'enabled',
        '_token',
        '_method'
    ];

    protected $casts = [
        'steps' => 'array'
    ];
}
