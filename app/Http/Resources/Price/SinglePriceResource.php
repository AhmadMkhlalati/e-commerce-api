<?php

namespace App\Http\Resources\Price;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SinglePriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $languages = Language::all()->pluck('code');

        // $nameTranslatable = [];

        // foreach ($languages as $language){
        //     $nameTranslatable[$language] = $this->getTranslation('name',$language);
        // }
        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'is_virtual' => (bool)$this->is_virtual,
            'currency_id' => $this->whenLoaded('currency')->id ,
            'original_price_id' => ($this->whenLoaded('originalPrice')->id) ?? null,
            'percentage' => (round($this->percentage,config('defaults.default_round_percentage'))) ?? null,
        ];
    }
}
