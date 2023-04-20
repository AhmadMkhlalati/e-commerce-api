<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Resources\Json\JsonResource;

class RestFullSettingResource extends JsonResource
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
            'value' => $this->value,
            'type' => $this->type,
            'is_developer' => $this->is_developer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];
    }
}
