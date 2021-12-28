<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            "email" => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|max:30|confirmed',
            'first_name' => 'required|min:2|max:40|regex:/^[\pL\s\-.]+$/u',
            'last_name' => 'required|min:2|max:40|regex:/^[\pL\s\-.]+$/u',
            'type_document' => 'required',
            'document' => 'required',
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
            'password.confirmed' => 'Las claves deben ser iguales',
            "name.required" => "El campo de nombre es obligatorio",
            "name.min" => "El nombre debe de ser mayor a :min caracteres",
            "name.max" => "El nombre no debe de ser mayor a :max caracteres",
            "name.regex" => "El nombre debe ser solo acepta letras, espacios y puntos."
        ];
    }
}
