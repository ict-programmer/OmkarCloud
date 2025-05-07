<?php

namespace App\Http\Requests\Perplexity;

use Illuminate\Foundation\Http\FormRequest;

class CodeAssistantRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'model' => ['nullable', 'string', 'in:sonar-reasoning,sonar-reasoning-pro'],
            'query' => ['required', 'string'],
            'programming_language' => ['nullable', 'string'],
            'code_length' => ['nullable', 'string', 'in:short,medium,long'],
        ];
    }
}
