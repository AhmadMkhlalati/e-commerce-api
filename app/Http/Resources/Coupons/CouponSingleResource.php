<?php

namespace App\Http\Resources\Coupons;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponSingleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $type = '';
        $value = '';

        if(!is_null($this->discount_percentage)){
            $type = 'percentage';
            $value = $this->discount_percentage;
        }else{
            $type = 'amount';
            $value = $this->discount_amount;
        }
        return [
            'id' => (int)$this->id,
            'title' => $this->title,
            'code' => $this->code,
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'value' => $value,
            'type' => $type,
            'min_amount' => $this->min_amount,
            'is_one_time' => $this->is_one_time,
            'is_used' => $this->is_used,
        ];
    }
}
