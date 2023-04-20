<?php

namespace App\Http\Requests\Tax;

use App\Http\Requests\MainRequest;
use App\Models\Tax\Tax;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StoreTaxRequest extends FormRequest
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
    public function rules(Request $request)
    {


        [];

        return [
            'name' => 'required',
            'is_complex' => 'required | boolean',
            'percentage' => ['required_if:is_complex,false', 'nullable', 'numeric', 'between:' . Tax::$minimumTaxPercentage . ',' . Tax::$maximumTaxPercentage],
            //''complex_behavior' => 'required_if:is_complex,true | in:'. Tax::$taxTypes,
            'complex_behavior' => ['required_if:is_complex,true', Rule::when($request->is_complex, 'in:' . Tax::$taxTypes)],

            'components' => 'required_if:is_complex,true',
            'components.*'  => 'required_if:is_complex,true | integer | exists:taxes,id',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the :attribute field is required.',

            'is_complex.required' => 'the :attribute field is required.',
            'is_complex.boolean' => 'The :attribute field accepts only 0 or 1',

            'percentage.required' => 'the :attribute field is required.',
            'percentage.numeric' => 'The :attribute must be decimal.',

            'complex_behavior.in' => 'The :attribute is not a valid type',


            'components.required_if' => 'the :attribute field is required.',

            'components.*.component_tax_id.required_if' => 'the component_tax_id field is required.',
            'components.*.component_tax_id.integer' =>  'the component_tax_id must be an integer',
            'components.*.component_tax_id.exists' =>  'the component_tax_id must be exists in taxes',

            'components.*.sort.required_if' => 'the sort field is required.',
            'components.*.sort.integer' =>  'the sort must be an integer',

        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                [
                    'code' => -1,
                    'errors' => $validator->errors()->messages(),
                ],
                200
            )

        );
    }
}
