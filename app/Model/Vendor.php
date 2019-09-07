<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name', 'phone','vendor_status','contact_person','email','address','company','logo'
    ];
}
