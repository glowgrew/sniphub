<?php

namespace App\Http\Requests\Snippet;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSnippetRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'body' => ['sometimes', 'string'],
            'categoryId' => ['sometimes', 'exists:categories,id'],
            'password' => ['sometimes','string'],
            'isPublic' => ['boolean', 'sometimes'],
            'expirationTime' => ['sometimes', 'date'],
        ];
    }
}
