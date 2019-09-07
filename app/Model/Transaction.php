<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $table = 'transaction';
    protected $fillable = [
        
        'tr_amount',
        'tr_id',
        'tr_type',
        'tr_ac_type',
        'tr_ac',
        'tr_date',
        'tr_note',
        'file',
        'flag'
       
        
    ];
}
