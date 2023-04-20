<?php

namespace App\Http\Resources\Brand;

use App\Http\Resources\Field\FieldResourceEntity;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Field\FieldsValueResource;
use App\Http\Resources\Label\SingleLableResource;
use App\Models\Language\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleBrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $labels = $this->whenLoaded('label') ?  $this->whenLoaded('label')->pluck('id') :[];
        $fieldsValues = $this->whenLoaded('fieldValue') ?? [];


        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'code' => $this->code,
            'image' => !empty($this->image) ?  getAssetsLink('storage/' . $this->image) : null,
            'meta_title' => $this->getTranslations('meta_title'),
            'meta_description' => $this->getTranslations('meta_description'),
            'meta_keyword' => $this->getTranslations('meta_keyword'),
            'description' => $this->getTranslations('description'),
            'sort' => $this->sort,
            'is_disabled' => (bool) $this->is_disabled,
            'labels' => ($labels),
            'fields' => FieldResourceEntity::collection($fieldsValues),


        ];
    }
}
