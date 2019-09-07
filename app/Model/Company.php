<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    //
    protected $table = 'official_info';
    protected $fillable = [
        'company_name',
        'company_email',
        'company_address',
        'company_address2',
        'company_phone_one',
        'company_phone_two',
        'company_logo'
        
       
    ];
}
