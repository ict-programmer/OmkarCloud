<?php

namespace App\Http\Requests\Pexels;

use Illuminate\Foundation\Http\FormRequest;

class GetPopularVideosRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'min_width' => 'nullable|integer|min:1',
            'min_height' => 'nullable|integer|min:1',
            'min_duration' => 'nullable|integer|min:1',
            'max_duration' => 'nullable|integer|min:1|gte:min_duration',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:15',
        ];
    }

    public function messages(): array
    {
        return [
            'min_width.integer' => __('The min_width must be an integer.'),
            'min_width.min' => __('The min_width must be at least 1.'),

            'min_height.integer' => __('The min_height must be an integer.'),
            'min_height.min' => __('The min_height must be at least 1.'),

            'min_duration.integer' => __('The min_duration must be an integer.'),
            'min_duration.min' => __('The min_duration must be at least 1.'),

            'max_duration.integer' => __('The max_duration must be an integer.'),
            'max_duration.min' => __('The max_duration must be at least 1.'),
            'max_duration.gte' => __('The max_duration must be greater than or equal to min_duration.'),

            'page.integer' => __('The page must be an integer.'),
            'page.min' => __('The page must be at least 1.'),

            'per_page.integer' => __('The per_page must be an integer.'),
            'per_page.min' => __('The per_page must be at least 15.'),
        ];
    }
}
