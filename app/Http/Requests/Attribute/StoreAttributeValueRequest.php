<?php

namespace App\Http\Requests\Attribute;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeValueRequest extends MainRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'attribute_id' => 'required',
            'value' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'attribute_id.required' => 'The :attribute id is required',
            'value.required' => 'The value :attribute is required'
        ];

    }
}
