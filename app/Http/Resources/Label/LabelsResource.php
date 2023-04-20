<?php

namespace App\Http\Resources\Label;

use Illuminate\Http\Resources\Json\JsonResource;

class LabelsResource extends JsonResource
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
            'title' => $this->title,
            'entity' => $this->entity,
            'color' => $this->color,
            'image'=> $this->image && !empty($this->image) ?  getAssetsLink('storage/'.$this->image): 'default_image' ,

            'key' => $this->key,
        ];
    }
}
