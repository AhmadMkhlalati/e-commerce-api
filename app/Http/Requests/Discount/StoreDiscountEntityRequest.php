<?php

namespace App\Http\Requests\Discount;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\RequiredIf;

class StoreDiscountEntityRequest extends MainRequest
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
            'discount_id' => 'required',
            'category_id' =>  'required_without_all:brand_id,tag_id',
            'brand_id' =>  'required_without_all:category_id,tag_id',
            'tag_id' =>  'required_without_all:brand_id,category_id',

        ];
    }

    public function messages()
    {
        return [

            'discount_id.required' => 'the discount id field is required',
            '(category_id | brand_id | tag_id).required_without_all' => 'one of them is required',

        ];

    }
}
