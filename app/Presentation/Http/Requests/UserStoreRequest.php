<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->input('name')) ? trim($this->input('name')) : $this->input('name'),
            'email' => is_string($this->input('email')) ? strtolower(trim($this->input('email'))) : $this->input('email'),
            'username' => is_string($this->input('username')) ? trim($this->input('username')) : $this->input('username'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email')->whereNull('deleted_at'),
            ],
            'username' => [
                'nullable', 'string', 'max:60',
                Rule::unique('users', 'username')->whereNull('deleted_at'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe ser un texto.',
            'name.max' => 'El :attribute no puede superar :max caracteres.',

            'email.required' => 'El :attribute es obligatorio.',
            'email.email' => 'El :attribute debe ser un email válido.',
            'email.unique' => 'Ya existe un usuario con ese :attribute.',

            'username.string' => 'El :attribute debe ser un texto.',
            'username.max' => 'El :attribute no puede superar :max caracteres.',
            'username.unique' => 'Ese :attribute ya está en uso.',

            'password.required' => 'La :attribute es obligatoria.',
            'password.string' => 'La :attribute debe ser un texto.',
            'password.min' => 'La :attribute debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de la :attribute no coincide.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'email' => 'email',
            'username' => 'usuario',
            'password' => 'contraseña',
        ];
    }
}
