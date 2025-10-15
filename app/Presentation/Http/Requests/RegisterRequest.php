<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['bail', 'required', 'string', 'max:120'],
            'email' => ['bail', 'required', 'email', 'max:255', 'unique:users,email'],
            'username' => ['bail', 'nullable', 'string', 'max:60', 'unique:users,username'],
            'password' => ['bail', 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser texto.',
            'name.max' => 'El nombre no puede superar :max caracteres.',

            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo no tiene un formato válido.',
            'email.max' => 'El correo no puede superar :max caracteres.',
            'email.unique' => 'Ese correo ya está registrado.',

            'username.string' => 'El usuario debe ser texto.',
            'username.max' => 'El usuario no puede superar :max caracteres.',
            'username.unique' => 'Ese nombre de usuario ya está en uso.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}
