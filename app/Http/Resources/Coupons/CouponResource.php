<?php

namespace App\Http\Resources\Coupons;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'title' => $this->title,
            'code' => $this->code,
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'discount_percentage' => $this->discount_percentage,
            'discount_amount' => $this->discount_amount,
            'min_amount' => $this->min_amount,
        ];
    }
}
