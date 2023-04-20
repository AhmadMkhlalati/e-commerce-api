<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)

{
    $parentName = $this->whenLoaded('parent') ? $this->whenLoaded('parent')->name : '-';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
            'icon'=> $this->icon && !empty($this->icon) ?  getAssetsLink('storage/'.$this->icon): 'default_icon' ,
            'parent' => $parentName,
            'slug' => $this->slug,

            // 'parent_id' => $this->parent_id,
            // 'meta_title' => $this->meta_title,
            // 'meta_description' => $this->meta_description,
            // 'meta_keyword' => $this->meta_keyword,
            // 'meta_keyword' => $this->description,
            // 'sort' => $this->sort,
            // 'is_disabled' => $this->is_disabled,
            // 'children' => self::collection( $this->whenLoaded('children')),
            // 'labels' => LabelsResource::collection($this->whenLoaded('label')),
            // 'fields' => FieldsResource::collection($this->whenLoaded('fields')),
            // 'fieldsValues' => FieldsValueResource::collection($this->whenLoaded('fieldValue')),
            // 'tags' => TagResource::collection($this->whenLoaded('tags')),
            // 'discounts' => new discount($this->whenLoaded('discount')),
            // 'brands' => new CategoryResource($this->whenLoaded('brands')),
            // 'products' => new CategoryResource($this->whenLoaded('products')),

             ];
    }
}
