<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPriceResoruce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $price = $this->whenLoaded('prices');
        return [
            'id' => $this->id,
            'name' => $price->getTranslation('name','en'),
            'product_id' => (int)$this->product_id,
            'price_id' => (int)$this->price_id,
            'price' => (float)$this->price,
            'discounted_price' => (float)$this->discounted_price,
            'currency' => $price['currency'] ? $price['currency']->code . '-' .$price['currency']->symbol : '-',
        ];
    }
}
