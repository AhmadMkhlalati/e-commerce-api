<?php

namespace App\Http\Resources\Tax;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleTaxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $taxComponent=$this->whenLoaded('taxComponents')->pluck('component_tax_id');

        // $languages = Language::all()->pluck('code');

        // $nameTranslatable = [];

        // foreach ($languages as $language){
        //     $nameTranslatable[$language] = $this->getTranslation('name',$language);
        // }

        return[
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'is_complex' => (boolean)$this->is_complex,
            'percentage' => $this->percentage,
            'complex_behavior' => $this->complex_behavior,
            'components' =>$taxComponent,
        ];
    }
}
