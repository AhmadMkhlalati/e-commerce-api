<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class RestFullOrderResource extends JsonResource
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
            'prefix' => $this->prefix,
            'time' => $this->time,
            'customer' => $this->whenLoaded('customer') ?? [],
            'currency_rate' => $this->currency_rate,
            'total' => $this->total,
            'tax_total' => $this->tax_total,
            'discount_percentage' => $this->discount_percentage,
            'discount_amount' => $this->discount_amount,
            'coupon' => $this->whenLoaded('coupon') ?? [],
            'customer_comment' => $this->customer_comment,
            'order_status' => $this->whenLoaded('status') ?? [],
            'shipping_first_name' => $this->shipping_first_name,
            'shipping_last_name' => $this->shipping_last_name,
            'shipping_address_one' => $this->shipping_address_one,
            'shipping_address_two' => $this->shipping_address_two,
            'shipping_city' => $this->shipping_city,
            'shipping_company_name' => $this->shipping_company_name,
            'shipping_country' => $this->whenLoaded('shippingCountry') ?? [],
            'shipping_email' => $this->shipping_email,
            'date' => $this->date,
            'shipping_phone_number' => $this->shipping_phone_number,
            'payment_method' => $this->whenLoaded('paymentMethod') ?? [],
            'billing_first_name' => $this->billing_first_name,
            'billing_last_name' => $this->billing_last_name,
            'billing_address_one' => $this->billing_address_one,
            'billing_address_two' => $this->billing_address_two,
            'billing_company_name' => $this->billing_company_name,
            'billing_city' => $this->billing_city,
            'billing_country' => $this->whenLoaded('billingCountry') ?? [],
            'billing_email' => $this->billing_email,
            'billling_phone_number' => $this->billling_phone_number,
            'billing_customer_notes' => $this->billing_customer_notes,
            'products' => $this->whenLoaded('products') ?? [],
            'notes' => $this->whenLoaded('notes') ?? [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,


        ];
    }
}
