<?php

namespace App\Http\Resources\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

class RestFullCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'address' => $this->whenLoaded('addresses') ?? [],
            'email' => $this->email,
            'phone' => $this->phone,
            'is_blacklist' => $this->is_blacklist,
            'blacklist_reason' => $this->blacklist_reason,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
