<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EditPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:posts,title,' . $this->route('post')->id],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug,' . $this->route('post')->id],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['required', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'summary' => ['nullable', 'string'],
            'body' => ['nullable', 'string'],
            'questions' => ['nullable', 'string'],
            'sources' => ['nullable', 'string'],
        ];
    }
}
