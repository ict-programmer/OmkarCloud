<?php

namespace App\Http\Requests\Perplexity;

use Illuminate\Foundation\Http\FormRequest;

class AiSearchRequest extends FormRequest
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
            'model' => 'nullable|string|in:sonar-pro,sonar',
            'query' => 'required|string',
            'search_type' => 'required|string|in:web,news',
            'max_results' => 'nullable|integer|min:0',
            'temperature' => 'nullable|numeric|min:0|lt:2',
        ];
    }

    public function messages(): array
    {
        return [
            'model.in' => __('The selected model is invalid. Valid values: sonar-pro,sonar'),
            'search_type.in' => __('The selected search type is invalid. Valid values: web,news'),
        ];
    }
}
