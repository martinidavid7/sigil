<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secretary extends Model
{

    
    //por algum motivo, foi necessario informar o nome da tabela
    protected $table = 'secretarys';
   
    protected $fillable = ['secretary', 
                            'responsible_name', 
                            'address', 
                            'phone', 
                            'neighborhood', 
                            'number', 
                            'zip_code', 
                            '_token', 
                            '_method'];
                            
}
