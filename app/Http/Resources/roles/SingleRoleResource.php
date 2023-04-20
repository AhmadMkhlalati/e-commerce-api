<?php

namespace App\Http\Resources\roles;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleRoleResource extends JsonResource
{
    protected $permissions;

    public function permissions($value){
        $this->permissions = $value;
        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parentId = $this->parent ? $this->parent->id : '';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_role' => $parentId,
            'permissions' => $this->permissions
//            'children' => self::collection($this->whenLoaded('children')),

        ];
    }
}
