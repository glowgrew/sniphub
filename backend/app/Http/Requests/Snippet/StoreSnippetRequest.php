<?php

namespace App\Http\Requests\Snippet;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSnippetRequest extends FormRequest
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
            'title' => ['string'],
            'body' => ['required', 'string'],
            'categoryId' => ['exists:categories,id'],
            'password' => ['string'],
            'burnAfterRead' => ['boolean'],
            'isPublic' => ['boolean'],
            'expirationTime' => ['required', 'date'],
        ];
    }
}
