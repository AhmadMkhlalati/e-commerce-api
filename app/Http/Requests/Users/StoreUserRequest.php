<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\MainRequest;
class StoreUserRequest extends MainRequest
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
            'username' => 'required|unique:users,username',
            'is_disabled' => 'boolean',
            'email' => 'required|email|unique:users,email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'salt' => 'nullable',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
            'role_id' => 'required|exists:roles,id'
        ];

//        if($this->has('id')){
//            $array['id'] = 'required|numeric|exists:users,id';
//            $array['email'] =  "required|email|unique:users,email,".$this->id.',id';
//            $array['username'] =  "required|string|unique:users,username,".$this->id.',id';
//        }
    }
}
