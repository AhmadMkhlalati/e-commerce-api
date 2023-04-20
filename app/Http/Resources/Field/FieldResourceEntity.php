<?php

namespace App\Http\Resources\Field;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResourceEntity extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
    //     $arrayToBeReturned = [
    //         'field_id' => $this->field_id,
    //         'value' => $this->value,
    //         'type' => $this->field->type
    //     ];

    //     if($this->field->type == 'select'){
    //         $arrayToBeReturned['value'] =$this->field_value_id;
    //     }

    //     if($this->field->type == 'checkbox'){
    //         $arrayToBeReturned['value'] = (bool)$this->value;
    //     }

    //     return $arrayToBeReturned;

        return [
            'field_id' => $this->field_id,
            'value' =>$this->value,
            'type' => $this->field->type

        ];
    }
}
