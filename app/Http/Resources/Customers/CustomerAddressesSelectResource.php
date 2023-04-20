<?php

namespace App\Http\Resources\Customers;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAddressesSelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            "id" => $this->id,
            "value" => $this->city . ' ' .$this->street . ' ' . $this->address_1,
            "data" => [
                "first_name" => $this->first_name,
                "last_name" => $this->last_name,
                "company_name" => $this->company_name,
                "address_1" => $this->address_1,
                "address_2" => $this->address_2,
                "city" => $this->city,
                "country_id" => (int)$this->country_id,
                "phone_number" => $this->phone_number,
                "email_address" => $this->email_address,
                "payment_method_id" => (int)$this->payment_method_id,
                "edit_type" => null,

            ],

        ];
    }
}
