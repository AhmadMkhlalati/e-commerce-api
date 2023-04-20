<?php

namespace App\Http\Requests\Countries;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class   StoreCountryRequest extends FormRequest
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
            'iso_code_1' => 'required | max:'.config('defaults.default_string_length'),
            'iso_code_2' => 'required | max:'.config('defaults.default_string_length'),
            'phone_code' => ['required' , 'max:6' , 'regex:/^\+\d{1,3}$/'],
            'flag' => 'required | file | max:'.config('defaults.default_string_length').'
                | mimes:'.config('defaults.default_icon_extentions').'
                | max:'.config('defaults.default_icon_size').'
                | dimensions:max_width='.config('defaults.default_icon_maximum_width').',max_height='.config('defaults.default_icon_maximum_height')

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The :attribute field is required.',

            'iso_code_1.required' => 'The :attribute is required.',
            'iso_code_1.max' => 'Invalid length for :attribute!',

            'is_code_2.required'  => 'The :attribute is required.',
            'is_code_2.max'  => 'Invalid length for :attribute!',

            'phone_code.required' => 'The :attribute is required.',
            'phone_code.max' => 'Invalid length for :attribute!',
            'phone_code.regex' => 'Invalid format for :attribute!',

            'flag.max' => 'The maximum :attribute size is :max.',
            'flag.mimes' => 'Invalid extention.',
            'flag.dimensions' => 'Invalid dimentions! maximum('.config('defaults.default_icon_maximum_width').'x'.config('defaults.default_icon_maximum_height').')',


        ];
    }
}
