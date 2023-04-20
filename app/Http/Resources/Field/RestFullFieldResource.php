<?php

namespace App\Http\Resources\Field;

use Illuminate\Http\Resources\Json\JsonResource;

class RestFullFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fieldValues=$this->whenLoaded('fieldValue');

        return [
            'id' =>$this->id,
            'title'=> $this->getTranslations('title'),
            'type'=> $this->type,
            'entity'=> $this->entity,
            'is_required'=> (bool)$this->is_required,
            'is_attribute'=> (bool)$this->is_attribute,
            'field_values' => ($fieldValues),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
