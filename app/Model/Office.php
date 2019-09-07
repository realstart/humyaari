<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    //
    protected $table = 'income';
    protected $fillable = [
        'income_date',
        'income_amount',
        'income_note',
        'account_no'
        
    ];
    
}
