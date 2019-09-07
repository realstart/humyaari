<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    //
    protected $table = 'sms_template';

    protected $fillable = [
        'template_category',
        'template_status',
        'template_title',
        'template_body',
        'template_serial',
        
    ];
}
