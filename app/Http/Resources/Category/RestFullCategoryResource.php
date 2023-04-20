<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class RestFullCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $labels = $this->whenLoaded('label') ?? [];
        $fields = $this->whenLoaded('fields') ?? [];

        return [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'code' => $this->code,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'icon'=> $this->icon && !empty($this->icon) ?  getAssetsLink('storage/'.$this->icon): 'default_icon' ,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id,
            'meta_title' =>  $this->getTranslations('meta_title'),
            'meta_description' => $this->getTranslations('meta_description'),
            'meta_keyword' => $this->getTranslations('meta_keyword'),
            'description' => $this->getTranslations('description'),
            'sort' => $this->sort,
            'is_disabled' => $this->is_disabled,
            'labels' => $labels,
            'fields' => $fields,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
