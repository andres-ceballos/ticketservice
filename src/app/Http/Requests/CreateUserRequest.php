<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->isAdmin()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'PUT':
                $id = $this->route('admin');

                return [
                    'firstname' => 'required|string|between:1,255',
                    'lastname' => 'required|string|between:1,255',
                    'email' => 'required|string|between:1,255|unique:users,email,' . $id,
                    'phone_ext' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|between:3,13',
                    'role_id' => 'required|in:1,2,3',
                    'password' => 'confirmed',
                ];
                break;

            default:
                return [
                    'firstname' => 'required|string|between:1,255',
                    'lastname' => 'required|string|between:1,255',
                    'email' => 'required|string|between:1,255|unique:users,email',
                    'phone_ext' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|between:3,13',
                    'role_id' => 'required|in:1,2,3',
                    'password' => 'required|string|between:8,255|confirmed',
                    'password_confirmation' => 'required'
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'firstname.required' => 'El campo nombre es obligatorio.',
            'lastname.required' => 'El campo apellidos es obligatorio.',
            'email.required' => 'El campo email es obligatorio.',
            'phone_ext.required' => 'El campo ext. teléfono es obligatorio.',
            'role_id.required' => 'El campo rol es obligatorio.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password_confirmation.required' => 'El campo es obligatorio.'
        ];
    }
}
