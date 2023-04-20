<?php

namespace App\Http\Requests\Discount;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends MainRequest
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
            'start_date' => 'required | date',
            'end_date' => 'nullable | date | after:start_date',
            'discount_percentage' => 'required | between:0,100 | numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the :attribute field is required',

            'start_date.required' => 'the :attribute field is required',
            'start_date.date' => 'the :attribute should be a date',

            'end_date.after' => 'the :attribute should be greater than the start_date',
            'end_date.date' => 'the :attribute should be a date',

            'discount_percentage.required' => 'the :attribute field is required',
            'discount_percentage.between' => 'the :attribute should be between 0 and 100 percent',
            'discount_percentage.doubleval' => 'the :attribute should be an integer or decimal',

        ];
    }
}
