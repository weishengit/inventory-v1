<?php

namespace App\Http\Requests\Accounts;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'password' => ['required', 'confirmed', 'string', 'max:255', 'min:8'],
            'avatar' => ['required', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            'role_id' => ['required', 'numeric', 'exists:roles,id']
        ];
    }
}

