<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => [
                'required',
                'string',
                'min:2',
                'max:2000',
            ],

            'parent_id' => [
                'nullable',
                'integer',
                'exists:comments,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Escribe un comentario.',
            'body.min' => 'El comentario debe tener al menos 2 caracteres.',
            'body.max' => 'El comentario no puede superar los 2000 caracteres.',
            'parent_id.exists' => 'El comentario que intentas responder no existe.',
        ];
    }
}
