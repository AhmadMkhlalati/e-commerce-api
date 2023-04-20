<?php

namespace App\Http\Resources;

use App\Models\Attribute\AttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $attributesValues= $this->whenLoaded('attributeValues');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'attributes_values' =>  AttributeValueResource::collection($attributesValues)
        ];
    }
}
