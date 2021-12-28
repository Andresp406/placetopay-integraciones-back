<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            "email" => 'required|email',
            'password' => 'required|string|min:6|max:30'
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "El campo de email es obligatorio",
            "email.email"   => 'El formato del campo de email es invalido',
            "password.required"  =>"La clave es obligatoria",
            "password.min" => "La clave debe de ser mayor a :min caracteres",
            "password.max" => "La clave no debe de ser mayor a :max caracteres",
        ];
    }
}
