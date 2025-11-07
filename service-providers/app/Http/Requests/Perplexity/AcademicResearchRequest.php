<?php

namespace App\Http\Requests\Perplexity;

use Illuminate\Foundation\Http\FormRequest;

class AcademicResearchRequest extends FormRequest
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
            'model' => 'nullable|string|in:sonar-deep-research',
            'query' => 'required|string',
            'search_type' => 'nullable|string|in:academic',
            'max_results' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'model.in' => __('The selected model is invalid. Valid value: sonar-deep-research'),
            'search_type.in' => __('The selected search type is invalid. Valid value: academic'),
        ];
    }
}
