<?php

namespace App\Http\Requests\ClaudeAPI;

use Illuminate\Foundation\Http\FormRequest;

class CodegenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:30720' // 30MB
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
            'description.required' => 'The description field is required.',
            'description.string' => 'The description field must be a string.',
            'attachments.array' => 'The attachments field must be an array.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
        ];
    }
}
