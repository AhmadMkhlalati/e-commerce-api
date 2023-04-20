<?php

namespace App\Http\Requests\Field;

use App\Http\Requests\MainRequest;
use App\Models\Field\Field;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //check if the riole has permission
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(Request $request)
    {
        return [
            'title' => 'required',
            'type' => ['required', ' in:' . implode(',', Field::$fieldTypes), Rule::when($request->is_attribute, ['in:select'])],
            'entity' => 'required | in:' . Field::$entities,
            'is_required' => 'required | boolean',
            'is_attribute' => 'required | boolean',

            'field_values' => 'required_if:type,select',
            'field_values.*'  => 'required_if:type,select',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The :attribute is required',

            'type.required' => 'The :attribute is required',
            'type.in' => 'The :attribute is not a valid type',

            'entity.required' => 'The :attribute is required',
            'entity.in' => 'The :attribute is not a valid type',

            'is_required.required' => 'The :attribute is required',

            'is_required.required' => 'The :attribute is required',
            'is_required.boolean' =>  'The :attribute accepts only 0 or 1',


            'field_value.required_if' => 'the field_value field is required.',
            'field_value.*.field_id.required_if' => 'the field_id field is required.',
            'field_value.*.field_id.integer' =>  'the field_id must be an integer',
            'field_value.*.field_id.exists' =>  'the field_id must be exists in taxes',

            'field_value.*.value.required_if' => 'the value field is required.',
        ];
    }
}
