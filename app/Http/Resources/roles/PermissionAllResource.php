<?php

namespace App\Http\Resources\roles;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionAllResource extends JsonResource
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
            'label' => $this->name,
            'nodes' => self::collection($this->whenLoaded('children')),

        ];
    }
}
