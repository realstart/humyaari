<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        //    return [
        //     'id'     => $this->id, 
        //     'name'   => $this->CustomerName,
        //     'phone'  => $this->phone,
        //     'invoice_no' => $this->invoice_no,
        //     'total_receivable' => $this->total_receivable,
        //     'status' => $this->status,
        //     'date' => $this->invoice_date
        // ];
        return [
            'data' => $this->collection,
            'links' => [
                'self' => 'link-value',
            ],
        ];
    }
}
