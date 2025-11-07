<?php

namespace App\Http\Requests\Pexels;

use Illuminate\Foundation\Http\FormRequest;

class GetFeaturedCollectionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:15',
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => __('The page must be an integer.'),
            'page.min' => __('The page must be at least 1.'),
            'per_page.integer' => __('The per_page must be an integer.'),
            'per_page.min' => __('The per_page must be at least 15.'),
        ];
    }
}
