<?php

namespace App\Http\Resources\Country;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'name'=>$this->name,
            'iso_code_1'=>$this->iso_code_1,
            'iso_code_2'=>$this->iso_code_2,
            'phone_code'=>$this->phone_code,
            'flag'=> $this->flag && !empty($this->flag) ?  getAssetsLink('storage/'.$this->flag): 'default_image' ,

        ];
    }
}
