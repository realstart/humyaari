<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
   
    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'invoice_due_date',
        'base_fare',
        'tax',
        'discount',
        'other_agent',
        'service_agent',
        'iata_comission',
        'govt_tax',
        'airline_payment',
        'total_receivable',
        'profit',
        'status',
        'payment_method',
        'note',
        'invoices_status',
        'invoice_created_by',
        'partial_payment',
        'flag',
        
    ];
    protected $dates = ['invoice_date'];

    public function customer()
    {
        return $this->BelongsTo('App\Model\Customer');
    }

    public function vendor()
    {
        return $this->BelongsTo('App\Model\Vendor');
    }

    public function items()
    {
        return $this->hasMany('App\Model\InvoiceItem');
    }
}
