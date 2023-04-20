<?php

namespace App\Http\Requests\Attribute;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttributeRequest extends MainRequest
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
            'title' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The :attribute field is required.'
        ];
    }
}
