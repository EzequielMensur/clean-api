<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El :attribute es obligatorio.',
            'title.string' => 'El :attribute debe ser un texto.',
            'title.max' => 'El :attribute no puede superar :max caracteres.',

            'body.required' => 'El :attribute es obligatorio.',
            'body.string' => 'El :attribute debe ser un texto.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'título',
            'body' => 'contenido',
        ];
    }
}
