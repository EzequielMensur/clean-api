<?php

namespace App\Presentation\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:160'],
            'body' => ['sometimes', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'El :attribute debe ser un texto.',
            'title.max' => 'El :attribute no puede superar :max caracteres.',
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
