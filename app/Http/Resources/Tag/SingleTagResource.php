<?php

namespace App\Http\Resources\Tag;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleTagResource extends JsonResource
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
        //     $translatable[$language] = $this->getTranslation('name',$language);
        // }

        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
        ];
    }
}
