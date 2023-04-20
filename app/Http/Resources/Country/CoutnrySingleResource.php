<?php

namespace App\Http\Resources\Country;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class CoutnrySingleResource extends JsonResource
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
        // $translatable = [];
        // foreach ($languages as $language){
        //     $nameTranslatable[$language] = $this->getTranslation('name',$language);
        // }

        return [
            'id' => $this->id,
            'name'=> $this->getTranslations('name'),
            'iso_code_1'=>$this->iso_code_1,
            'iso_code_2'=>$this->iso_code_2,
            'phone_code'=>$this->phone_code,
            'flag'=> $this->flag && !empty($this->flag) ?  getAssetsLink('storage/'.$this->flag): 'default_image' ,

        ];
    }
}
