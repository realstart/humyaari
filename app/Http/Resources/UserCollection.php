<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        // return [
           
        //         'name' => $request->name,
        //         'email' => $request->email,
        //         'updated_at' => (string) $request->updated_at, 
        //      ];
           

        // return [
        //     //'id' => $this->id,
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'updated_at' => (string) $this->updated_at ,
            
        // ];
        
    }
}
