<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    //
    protected $fillable = [
        'note_title',
        'note_details',
        'note_date',
        
    ];
}
