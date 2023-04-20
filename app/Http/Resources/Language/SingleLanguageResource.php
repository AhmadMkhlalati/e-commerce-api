<?php

namespace App\Http\Resources\Language;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleLanguageResource extends JsonResource
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
            'code' => $this->code,
            'is_default' => (bool)$this->is_default,
            'is_disabled' => (bool)$this->is_disabled,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'sort' => $this->sort
        ];
    }
}
