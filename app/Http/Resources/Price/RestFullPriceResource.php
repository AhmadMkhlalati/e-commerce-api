<?php

namespace App\Http\Resources\Price;

use Illuminate\Http\Resources\Json\JsonResource;

class RestFullPriceResource extends JsonResource
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
            'name' => $this->getTranslations('name'),
            'is_virtual' => $this->is_virtual,
            'currency' => $this->whenLoaded('currency') ?? [] ,
            'original_price_id' => ($this->whenLoaded('originalPrice')) ?? null,
            'percentage' => ($this->percentage),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
