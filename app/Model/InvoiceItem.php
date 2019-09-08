<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'type',
        'name',
        'pnr',
        'route',
        'quantity',
        'price',
        'total',
        'carrier',
        'e_ticket'
    ];
}
