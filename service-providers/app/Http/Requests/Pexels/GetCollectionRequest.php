<?php

namespace App\Http\Requests\Pexels;

use App\Enums\PexelsMediaTypeEnum;
use App\Enums\SortOrderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetCollectionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|string',
            'type' => ['nullable', 'string', Rule::in(array_column(PexelsMediaTypeEnum::cases(), 'value'))],
            'sort' => ['nullable', 'string', Rule::in(array_column(SortOrderEnum::cases(), 'value'))],
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:15',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The id field is required.',
            'id.string' => 'The id must be a string.',
            'type.in' => 'The type must be one of: ' . implode(', ', array_column(PexelsMediaTypeEnum::cases(), 'value')),
            'sort.in' => 'The sort must be one of: ' . implode(', ', array_column(SortOrderEnum::cases(), 'value')),
            'page.integer' => 'Page must be an integer.',
            'page.min' => 'Page must be at least 1.',
            'per_page.integer' => 'Per page must be an integer.',
            'per_page.min' => 'Per page must be at least 15.',
        ];
    }

    public function validationData(): array
    {
        return array_merge($this->all(), $this->route()?->parameters() ?? []);
    }
}
