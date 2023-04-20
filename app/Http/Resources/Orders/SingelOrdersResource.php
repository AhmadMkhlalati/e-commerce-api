<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class SingelOrdersResource extends JsonResource
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
            'code' => $this->id,
            "client_id" => (int)$this->customer_id,
            "currency_id" => (int)$this->currency_id,
            "shipping_address_id" => is_null($this->shipping_address_id) ? null : (int) $this->shipping_address_id,
            "billing_address_id" =>  is_null($this->billing_address_id) ? null : (int) $this->billing_address_id,
            "time" => $this->time,
            "currency_rate" => $this->currency_rate,
            "total_price" => $this->total,
            'is_billing_as_shipping' => (bool)$this->is_billing_as_shipping,
            'notes' => OrdersNotesResource::collection($this->whenLoaded('notes')),
            'shipping_company_id' => null, // to be added after adding the shipping companies
            "date" => $this->date,
            "comment" => $this->customer_comment,
            "payment_method" => 1,
            "status_id" =>  is_null($this->order_status_id) ? null : (int) $this->order_status_id,
            "prefix" => $this->prefix,
            "coupon_code" => $this->whenLoaded('coupon') ? $this->whenLoaded('coupon')->code : '',

            "billing" => [
                "first_name" => $this->billing_first_name,
                "last_name" => $this->billing_last_name,
                "company_name" => $this->billing_company_name,
                "address_1" => $this->billing_address_one,
                "address_2" => $this->billing_address_two,
                "city" => $this->billing_city,
                "country_id" =>  is_null($this->billing_country_id) ? null : (int) $this->billing_country_id,
                "phone_number" => $this->billing_phone_number,
                "email_address" => $this->billing_email,
                "payment_method_id" =>  is_null($this->payment_method_id) ? null : (int) $this->payment_method_id,
                "edit_type" => "done",

            ],
            "shipping" => [
                "first_name" => $this->shipping_first_name,
                "last_name" => $this->shipping_last_name,
                "company_name" => $this->shipping_company_name,
                "address_1" => $this->shipping_address_one,
                "address_2" => $this->shipping_address_two,
                "city" => $this->shipping_city,
                "country_id" => is_null($this->shipping_country_id) ? null : (int) $this->shipping_country_id,
                "phone_number" => $this->shipping_phone_number,
                "email_address" => $this->shipping_email,
                "edit_type" => "done",
            ],
            'selected_products' => $this->selected_products,

        ];
    }
}
