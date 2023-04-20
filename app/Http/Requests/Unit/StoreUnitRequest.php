<?php

namespace App\Http\Requests\Unit;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends MainRequest
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
            'name' => 'required',
            'code' => 'required | max:'.config('defaults.default_string_length')
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the name field is required',
            'code.required' => 'the code field is required',
            'code.max' => 'the maximum string length is :max',


        ];
    }
}
