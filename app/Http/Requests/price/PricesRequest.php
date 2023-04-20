<?php

namespace App\Http\Requests\price;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;

class PricesRequest extends MainRequest
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
            'currency_id' => 'required|exists:currencies,id',
            'is_virtual' => 'boolean|required',
            'original_price_id' => ' nullable | required_if:is_virtual,"true"|exists:prices,id',
            'percentage' => ' nullable | required_if:is_virtual,"true"| numeric | min:'.config('defaults.default_minimum_price_percentage'),

            'data' => 'nullable',
            'data.currency_name' => 'nullable',
            'data.original_price_name' => 'nullable',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the :attribute field is required',

            'is_virtual.boolean' => 'the :attribute field must be a boolean',
            'is_virtual.required' => 'the :attribute field is required',

            'currency_id.required' => 'the currency field is required',
            'currency_id.exists' => 'the currency field is invalid',

            'original_price_id.required_if' => 'the parent class field is required',
            'original_price_id.exists' => 'the parent class field is invalid',

            'percentage.required_if' => 'the :attribute field is required',
            'percentage.numeric' => 'the :attribute field must be a numeric',
            'percentage.between' => 'the :attribute field must be between '.config('defaults.default_minimum_price_percentage').' and '.config('defaults.default_maximum_price_percentage'),

        ];
    }
}
