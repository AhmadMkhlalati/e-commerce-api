<?php

namespace App\Http\Resources\Brand;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,
//            'is_disabled' => (boolean)$this->is_disabled,

            // 'meta_title' => $this->meta_title,
            // 'meta_description' => $this->meta_description,
            // 'meta_keyword' => $this->meta_keyword,
            // 'description' => $this->description,
            // 'keyword' => $this->keyword,
            // 'sort' => $this->sort,
        ];
    }
}
