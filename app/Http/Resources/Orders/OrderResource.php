<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            "customer_first_name" => $this->whenLoaded('customer') ? $this->whenLoaded('customer')->first_name ?? '-' : '-',
            "customer_last_name" => $this->whenLoaded('customer') ? $this->whenLoaded('customer')->last_name ?? '-' : '-',
            "time" => $this->time ?? '00:00:00',
            "date" => $this->date ?? '00-00-0000',
            'total' => $this->total ?? 0 ,

//            "status_id" => $this->whenLoaded('status') ? $this->whenLoaded('status')->name : '-',
//            "comment" => $this->customer_comment,
//            "shipping_as_billing" => false,
//            "coupon_code" => $this->whenLoaded('coupon') ? $this->whenLoaded('coupon')->title : '-' ,
        ];
    }

    public static function customCollection($resource, $data): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        //you can add as many params as you want.
        self::$data = $data;
        return parent::collection($resource);
    }
}
