<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $name = is_string($this->input('name'))
            ? preg_replace('/\s+/', ' ', trim($this->input('name')))
            : $this->input('name');

        $this->merge([
            'name' => $name,
            'email' => is_string($this->input('email')) ? strtolower(trim($this->input('email'))) : $this->input('email'),
            'username' => $this->filled('username') && is_string($this->input('username'))
                ? strtolower(trim($this->input('username')))
                : $this->input('username'),
        ]);
    }

    public function rules(): array
    {
        $userId = (int) $this->route('id');

        return [
            'name' => ['bail', 'sometimes', 'required', 'string', 'max:120'],
            'email' => ['bail', 'sometimes', 'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'username' => ['bail', 'sometimes', 'nullable', 'string', 'max:60',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'password' => ['bail', 'sometimes', 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio cuando se envía.',
            'name.string' => 'El nombre debe ser texto.',
            'name.max' => 'El nombre no puede superar :max caracteres.',

            'email.required' => 'El correo es obligatorio cuando se envía.',
            'email.email' => 'El correo no tiene un formato válido.',
            'email.max' => 'El correo no puede superar :max caracteres.',
            'email.unique' => 'Ese correo ya está registrado por otro usuario.',

            'username.string' => 'El nombre de usuario debe ser texto.',
            'username.max' => 'El nombre de usuario no puede superar :max caracteres.',
            'username.unique' => 'Ese nombre de usuario ya está en uso.',

            'password.required' => 'La contraseña es obligatoria cuando se envía.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}
