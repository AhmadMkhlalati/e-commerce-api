<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\MainRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends MainRequest
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
            'username' => ['required', Rule::unique('users')->ignore($this->user->id)],
            'is_active' => 'required|boolean',
            'email' =>  ['required','email', Rule::unique('users')->ignore($this->user->id)],
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'salt' => 'nullable',
            'role_id' => 'required|exists:roles,id'
        ];

    }
}
