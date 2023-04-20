<?php

namespace App\Http\Resources\Orders;

use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersNotesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->date)->toString();
        return [
            'id' => $this->id,
            'note' => $this->body,
            'title' => $this->body,
            'username' => User::find($this->user_id) ? User::find($this->user_id)->username : 'NON',
            'date' => $date,
        ];
    }
}
