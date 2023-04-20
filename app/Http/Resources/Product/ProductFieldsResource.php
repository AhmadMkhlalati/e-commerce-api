<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductFieldsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {

        $value = $this->value;
        if(!is_null($this->field_value_id)){
            $value = (int)$this->field_value_id;
        }


        // $currentObject->id = $this->id
        return [
            'id' => (int)$this->id,
            'field_id' => (int)$this->field_id,
            'value' => is_numeric($value) ? (int)($value) : $value,
        ];
    }


}
