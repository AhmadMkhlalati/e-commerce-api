<?php

namespace App\Http\Requests\Setting;

use App\Http\Requests\MainRequest;
use App\Rules\SettingValueRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreSettingRequest extends FormRequest
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
        return [

            'type' => 'required | string',
            'value' => ['required', 'max:' . config('defaults.default_string_length'), new SettingValueRule($request->type, $this->setting->id)],
        ];
    }

    public function messages()
    {
        return [

            'value.required' => 'the :attribute field is required',
            'value.max' => 'the maximum string length is :max',
        ];
    }
}
