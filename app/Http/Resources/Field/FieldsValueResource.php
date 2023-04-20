<?php

namespace App\Http\Resources\Field;

use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class FieldsValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//
//        $languages = Language::all()->pluck('code');
//        $translatable = [];
//
//        foreach ($languages as $language){
//            $valueTranslatable[$language] = $this->getTranslation('value',$language);
//        }

        return [
            'id' => $this->id,
            'field_id' => $this->field_id,
            'value' => $this->value,

        ];
    }
}
