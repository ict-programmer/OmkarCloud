<?php

namespace App\Http\Requests\Pexels;

use App\Enums\PexelsMediaSizeEnum;
use App\Enums\PexelsOrientationEnum;
use Illuminate\Foundation\Http\FormRequest;

class SearchVideosRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => 'required|string',
            'orientation' => 'nullable|string|in:' . implode(',', array_column(PexelsOrientationEnum::cases(), 'value')),
            'size' => 'nullable|string|in:' . implode(',', array_column(PexelsMediaSizeEnum::cases(), 'value')),
            'locale' => 'nullable|string',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:15',
        ];
    }

    public function messages(): array
    {
        return [
            'query.required' => __('The query field is required.'),
            'query.string' => __('The query must be a string.'),
            'orientation.in' => __('The orientation must be one of: ' . implode(', ', array_column(PexelsOrientationEnum::cases(), 'value'))),
            'size.in' => __('The size must be one of: ' . implode(', ', array_column(PexelsMediaSizeEnum::cases(), 'value'))),
            'locale.string' => __('The locale must be a string.'),
            'page.integer' => __('The page must be an integer.'),
            'page.min' => __('The page must be at least 1.'),
            'per_page.integer' => __('The per_page must be an integer.'),
            'per_page.min' => __('The per_page must be at least 15.'),
        ];
    }
}
