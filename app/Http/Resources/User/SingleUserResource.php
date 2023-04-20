<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class SingleUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $role = 0;
        if( count($this->roles) > 0 ){
            $role = $this->roles[0]->id ?? 0;
        }

        return[
            'id' => (int)$this->id,
            'username' => $this->username,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role_id' => (int)$role,
            'is_active' => (bool)$this->is_active
        ];
    }
}
