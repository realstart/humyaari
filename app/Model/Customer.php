<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{

    protected $fillable = [
        'name',
        'address1',
        'address2',
        'phone',
        'email',
        'customer_status'
    ];
}
