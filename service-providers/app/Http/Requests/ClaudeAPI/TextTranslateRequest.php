<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Foundation\Http\FormRequest;

class TextTranslateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => 'required|string',
            'source_language' => 'required|string',
            'target_language' => 'required|string',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'text.required' => 'The text field is required.',
            'text.string' => 'The text field must be a string.',
            'source_language.required' => 'The source language field is required.',
            'source_language.string' => 'The source language field must be a string.',
            'target_language.required' => 'The target language field is required.',
            'target_language.string' => 'The target language field must be a string.',
        ];
    }
}
