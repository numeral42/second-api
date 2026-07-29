<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EditTagRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:tags,name,' . $this->route('tag')->id],
            'slug' => ['required', 'string', 'max:255', 'unique:tags,slug,' . $this->route('tag')->id],
            'color' => ['required', 'string', 'max:7'], // Assuming color is a hex code
        ];
    }
}
