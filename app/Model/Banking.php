<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banking extends Model
{
    protected $table = 'bank_details';
    protected $fillable = [
        'ac_holder_name',
        'ac_no',
        'bank_id',
        'opening_balance',
        'ac_type',
       
    ];
}
