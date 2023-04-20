<?php

namespace App\Http\Resources\Customers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Customers\CustomerAddressesSelectResource;
class SelectCustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $addresses = $this->whenLoaded('addresses');
        return [
            'id' => (int)$this->id,
            'value' => $this->first_name . ' ' . $this->last_name .' - ' . $this->phone,
            'addresses' => CustomerAddressesSelectResource::collection($this->whenLoaded('addresses')),

        ];
    }
}
