<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Utility extends Model
{
    //
    protected $table = 'expense';
    protected $fillable = [
        'expense_date',
        'expense_amount',
        'expense_note',
        'account_no',
        
       
    ];
}
