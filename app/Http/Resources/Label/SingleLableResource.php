<?php

namespace App\Http\Resources\Label;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleLableResource extends JsonResource
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
        // $titleTranslatable = [];

        // foreach ($languages as $language){
        //     $titleTranslatable[$language] = $this->getTranslation('title',$language);
        // }
        return [
            'id' => $this->id,
            'title' => $this->getTranslations('title'),
            'entity' => $this->entity,
            'color' => $this->color,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image) : 'default_image' ,

            'key' => $this->key,
        ];
    }
}
