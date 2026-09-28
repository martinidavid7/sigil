<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiddingProcedureStep extends Model
{
    //
    protected $table = 'bidding_procedures_steps';

    protected $fillable = [
        'step_name',
        //'document_number',
        //'situation',
        //'start_date',
        //'responsible',
        //'deadline',
        //'end_date',
        //'end_date',
        'order',
        'enabled',
        '_token',
        '_method'
    ];
}
