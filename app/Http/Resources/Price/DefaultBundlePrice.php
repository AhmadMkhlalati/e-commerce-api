<?php

namespace App\Http\Resources\Price;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultBundlePrice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
             $currency = $this->prices->currency->symbol;

        return [
            'id' => $this->price_id,
            'price'=> $this->price,
            'currency' => $currency,
        ];
    }
}
