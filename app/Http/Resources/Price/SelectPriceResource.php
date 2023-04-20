<?php

namespace App\Http\Resources\Price;

use Illuminate\Http\Resources\Json\JsonResource;

class SelectPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //try now please
        //wait localy cant you?
        //locally sh8ali w 3a km product sh8ali
                return [
            'id' => $this->id,
            'name' => $this->name,
            'currency' => ($this->whenLoaded('currency')->code.' - '.$this->whenLoaded('currency')->symbol)  ?? '-',
            'title_price_currency' => $this->name . ' - ' . ($this->whenLoaded('currency')->symbol)  ?? 'NA',
            // 'is_virtual' =>(bool)$this->is_virtual
        ];
    }
}
